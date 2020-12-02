import TeboUtility, { ICountry } from "../../TeboUtility";
import { Exam, ExamServiceEnum, IFormWizardConfig, IFormWizardViewModel, IGuardianData, IPersonalData, IPurchaseItem, IPurchaseRequest } from "./Utility";
import $ from 'jquery';
import ko from 'knockout';
import Parsley from 'parsleyjs';
import moment from 'moment';
import 'bootstrap-datepicker';
import { result } from "lodash";
import FormNotification from "../form-notification/FormNotification";
import ValidationError from "../app-error/ValidationError";




//this.notificationForm?.show(NotificationStatus.Danger, "Problem retrieving media location information");
export default class FormWizard {
    private wrapperEl: HTMLElement;
    private tabWrapperEl: Element;
    // private purchaseListWrapperEl: HTMLElement;
    private formEl: HTMLFormElement;
    private sectionWrapperEl: HTMLElement;

    private tabEls: HTMLElement[];
    private sectionEls: HTMLElement[];
    private payableTotalEl: HTMLElement;

    private prevButtonEl: HTMLButtonElement;
    private nextButtonEl: HTMLButtonElement;
    private submitButtonEl: HTMLButtonElement;

    
    private ddlPersonalCountryEl: HTMLInputElement
    private ddlGuardianCountryEl: HTMLInputElement;
    private ddlPersonalStateEl: HTMLInputElement;
    private ddlGuardianStateEl: HTMLInputElement

    private servicesCheckEls: HTMLInputElement[]; // not used
    // private supportedExams: Exam[];

    private currentSectionIndex: number;
    private lastSectionIndex: number;

    private itemTotal: Map<string, number[]>;

    private validationInstance;

    private wizardData: any[];
    
    private notificationForm: FormNotification | null = null;

    // private defaultSectionName: string;

    constructor(private config: IFormWizardConfig) {
        this.wizardData = [];
        this.itemTotal = new Map();

        this.wrapperEl = document.getElementById(this.config.targetId)!;
        this.tabWrapperEl = this.wrapperEl?.querySelector(this.config.tabWrapperSelector)!;
        this.formEl = document.querySelector(this.config.formSelector)! as HTMLFormElement;

        if (!this.wrapperEl) throw new Error("Unable to find form wizard");
        
        this.wrapperEl && this.displaySupportedExams(this.config.supportedExams);

        this.ddlPersonalCountryEl = document.getElementById("ddlPersonalCountry")! as HTMLInputElement;
        this.ddlGuardianCountryEl = document.getElementById("ddlGuardianCountry")! as HTMLInputElement;
        this.ddlPersonalStateEl = document.getElementById("ddlPersonalState")! as HTMLInputElement;
        this.ddlGuardianStateEl = document.getElementById("ddlGuardianState")! as HTMLInputElement;

        this.validationInstance = $(this.formEl).parsley({
            inputs: this.config.validatableControlSelector
        });
        this.sectionWrapperEl = this.wrapperEl?.querySelector(this.config.sectionWrapperSelector)! as HTMLElement;
        this.servicesCheckEls = Array.from(document.querySelectorAll(`.${this.config.serviceMarkerClass}`));
        this.payableTotalEl = document.querySelector(this.config.examPayableTotalSelector)! as HTMLElement;

        this.prevButtonEl = this.wrapperEl?.querySelector(this.config.prevButtonSelector)! as HTMLButtonElement;
        this.nextButtonEl = this.wrapperEl?.querySelector(this.config.nextButtonSelector)! as HTMLButtonElement;
        this.submitButtonEl = this.wrapperEl?.querySelector(this.config.submitButtonSelector)! as HTMLButtonElement;

        // first tab + section
        this.tabEls = Array.from(this.tabWrapperEl?.querySelectorAll(`:scope *[${this.config.sectionNameAttr}]`)!) as HTMLElement[];
        this.sectionEls = Array.from(this.sectionWrapperEl?.querySelectorAll(`:scope *[${this.config.sectionNameAttr}]`)!) as HTMLElement[];

        if (!this.wrapperEl || this.tabEls.length === 0 || this.sectionEls.length === 0)
            throw new Error("Invalid FormWizard document structure");

        // set the current and last section index
        this.currentSectionIndex = this.config.defaultSectionIndex;
        this.lastSectionIndex = this.tabEls.length - 1;

        this.registerCustomValidators();
        this.setup();

        document.getElementById("btnJustClick")?.addEventListener('click', this.sampleAction.bind(this), false);
    }

    loadCountryDropdown(selectedCountryCode: string) {
        this.ddlPersonalCountryEl.innerHTML = '';
        this.ddlGuardianCountryEl.innerHTML = '';

        let options = `<option ${selectedCountryCode ? '' : 'selected'} disabled value="">Select Country</option>`;

        for (const country of TeboUtility.allCountries) {
            options += `<option ${selectedCountryCode.toLocaleLowerCase() !== country.code.toLocaleLowerCase() ? '' : 'selected'}  value="${country.code}">${country.name}</option>`;
        }

        this.ddlPersonalCountryEl?.insertAdjacentHTML('beforeend', options);
        this.ddlGuardianCountryEl?.insertAdjacentHTML('beforeend', options);

        this.ddlPersonalCountryEl?.dispatchEvent(new Event('change'));
        this.ddlGuardianCountryEl?.dispatchEvent(new Event('change'));
    }

    loadStateDropdown(countryCode: string, ddl: HTMLInputElement) {
        let options = `<option ${countryCode.toLocaleLowerCase() === 'ng' ? 'selected' : ''} disabled value="">Select State</option>`;

        ddl.innerHTML = '';

        for (const state of TeboUtility.allStates.filter(state => state.countryCode.toLowerCase() === countryCode.toLowerCase())) {
            options += `<option value="${state.stateCode}">${state.stateName}</option>`;

        }

        options += `<option ${countryCode.toLocaleLowerCase() === 'ng' ? '' : 'selected'} value="Other">Other</option>`;

        ddl.insertAdjacentHTML('beforeend', options);
    }

    setup() {
        this.notificationForm = new FormNotification({
            containerId: 'student-application-notification',
            messageElClass: 'alert-message'
        });

        $('.date-control').datepicker({
            format: 'yyyy-mm-dd',
            weekStart: 1,
            daysOfWeekHighlighted: "6,0",
            autoclose: true,
            todayHighlight: true,
        });

        this.ddlPersonalCountryEl.addEventListener('change', e => {
            const el: HTMLInputElement = e.target as HTMLInputElement;
            const selectedValue: string = el.value.toLowerCase();

            this.loadStateDropdown(selectedValue, this.ddlPersonalStateEl);            
        });

        this.ddlGuardianCountryEl.addEventListener('change', e => {
            const el: HTMLInputElement = e.target as HTMLInputElement;
            const selectedValue: string = el.value.toLowerCase();

            this.loadStateDropdown(selectedValue, this.ddlGuardianStateEl);
        });

        this.loadCountryDropdown('NG');

        document.querySelectorAll(`[${this.config.examItemAttr}]`).forEach(listItemEl => {
            // const examName = listItemEl.getAttribute(this.config.examItemAttr)!;
            this.updateItemTotal(listItemEl as HTMLElement, 0);
        });

        // disable all following sections
        for (let i = this.currentSectionIndex + 1; i < this.tabEls.length; i++) {
            this.disableSection(i);
        }

        // create validation section of form
        const self = this;
        this.validationInstance.on('form:error', function () {
            this.fields.forEach((field, key) => {
                if (field.validationResult !== true) {
                    self.updateErrorClass(field.element, true);
                } else {
                    self.updateErrorClass(field.element, false);
                }
            });
        });

        this.validationInstance.on('form:success', function () {
            this.fields.forEach((field, key) => {
                if (field.validationResult === true) {
                    self.updateErrorClass(field.element, false);
                } else {
                    self.updateErrorClass(field.element, true);
                }
            });
        });

        this.validationInstance.on('field:validated', function () {
            if (this.validationResult !== true) {
                self.updateErrorClass(this.element, true);
            } else {
                self.updateErrorClass(this.element, false);
            }
        });

        this.sectionEls.forEach((el, index) => {
            el.querySelectorAll(this.config.validatableControlSelector.replace('input', 'input:not(.block-dependant)')).forEach(controlEl => {
                controlEl.setAttribute("data-parsley-group", `block-${index}`);
            });

            el.querySelectorAll(`[${this.config.examItemAttr}]`).forEach((listItemEl, listItemIndex) => {
                listItemEl.setAttribute(this.config.examItemIndexAttr, String(listItemIndex));
                listItemEl.querySelectorAll("input.block-dependant").forEach(controlEl => {
                    controlEl.setAttribute("required", ""); 
                    controlEl.setAttribute("data-parsley-group", "child-block-" + listItemIndex); 
                })
            });
        });

        document.querySelector(".list-group")?.addEventListener("change", e => {
            const el = e.target as HTMLInputElement;
            const listItemEl = TeboUtility.getClosest(el, `[${this.config.examItemAttr}]`) as HTMLElement;
            const serviceName = el.getAttribute("data-service")!;
            const servicePrice = +el.getAttribute("data-price")!;

            if (!serviceName || !servicePrice)
                return;

            const examName = listItemEl.getAttribute(this.config.examItemAttr)!;
            const serviceTotal = this.calcItemTotal(examName, servicePrice, el.checked);
            this.updateItemTotal(listItemEl as HTMLElement, serviceTotal);

            if (serviceName !== ExamServiceEnum.ExamRegistration) return;

            const examInfoEl = listItemEl.querySelector(".c-exam-list__reg-detail");
            examInfoEl?.classList.toggle("c-exam-list__reg-detail--show", el.checked);
        }, false);

        // show default section
        this.toggleButtonsVisibility(this.currentSectionIndex);
        this.showSection(this.currentSectionIndex);

        this.prevButtonEl.addEventListener("click", e => {
            const el = e.currentTarget as HTMLElement;

            const prevSectionIndex: number = this.getPrevSectionIndex();

            this.handlePrevSection(prevSectionIndex);
            
            e.stopPropagation();
        }, false);

        this.nextButtonEl.addEventListener("click", e => {
            const el = e.currentTarget as HTMLElement;

            // get current section
            // get next section
            const nextSectionIndex: number = this.getNextSectionIndex();

            this.handleNextSection(nextSectionIndex);
            
            e.stopPropagation();
        }, false);

        this.tabWrapperEl.addEventListener("click", e => {
            const el = e.target as HTMLElement;
            //if (e.target === e.currentTarget) return e.stopPropagation();

            const tabEl: HTMLElement = TeboUtility.getClosest(el, `[${this.config.sectionIndexAttr}]`);
            const sectionIndex: number = +tabEl.getAttribute(this.config.sectionIndexAttr)!;

            if (tabEl.classList.contains(this.config.tabDisabledClass)) return;
            if (this.currentSectionIndex === sectionIndex) return;

            // validate current section
            if (this.validateSection(this.currentSectionIndex)) {
                this.markSectionCompleted(this.currentSectionIndex);
            }
            
            if (this.currentSectionIndex < sectionIndex) {
                this.handleNextSection(sectionIndex);
            } else {
                this.handlePrevSection(sectionIndex);
            }

            e.stopPropagation();
        }, false); // bubble phase
    }

    displaySupportedExams(examList: Exam[]) {
        // this.purchaseListWrapperEl.innerHTML = 
        let html: string = '';
        const purchaseListWrapperEl = document.querySelector(this.config.purchaseListWrapperSelector)! as HTMLElement;

        if (!purchaseListWrapperEl) return;

        examList.forEach((exam, index) => {
            let checkHtml: string = '';
            let examRegElementId: string = '';
            let examName = exam.examName.toLowerCase();

            for (let [serviceName, servicePrice] of exam.servicePrices) {
                if (serviceName === ExamServiceEnum.ExamRegistration)
                    examRegElementId = `chkExamService-${serviceName}-${index}`;

                checkHtml += `
                    <div class="custom-control custom-switch mr-3">
                        <input type="checkbox" class="custom-control-input c-exam-list__service"
                            id="chkExamService-${serviceName}-${index}" name="chkExamService-${examName}" data-service="${serviceName}"
                            data-price="${servicePrice}" data-parsley-mincheck="0">
                        <label class="custom-control-label" for="chkExamService-${serviceName}-${index}">${this.getExamServiceName(serviceName)}</label>
                        <div class="w-100"></div>
                        <span class="c-exam-list__service-amount">NGN ${TeboUtility.numberFormatter(servicePrice, 0, '.', ',')}</span>
                    </div>
                `;
            }

            html += `
                <div ${this.config.examItemAttr}="${examName}" ${this.config.examItemIdAttr}="${exam.examId}" class="list-group-item c-exam-list__item">
                    <div class="d-flex flex-column flex-md-row">
                        <h5 class="pr-4 c-exam-list__item-title">${exam.examName}</h5>
                        <div class="flex-grow-0 d-flex flex-column align-items-start">
                            <div class="d-flex flex-row">
                            ${checkHtml}
                            </div>
                            <div data-parsley-require-if="#${examRegElementId}"
                                class="pt-4 d-flex-inline flex-wrap align-content-start c-exam-list__reg-detail">
                                <div class="d-flex">
                                    <div class="form-group mr-2">
                                        <small class="text-muted d-block mb-2">PREFERRED</small>
                                        <label for="dtpPreferredExamDate-${index}">Exam Date</label>
                                        <input type="text" class="form-control form-control-sm block-dependant date-control"
                                            id="dtpPreferredExamDate-${index}" name="dtpPreferredExamDate-${index}" 
                                            placeholder="DD/MM/YYYY" required data-parsley-dateformat>
                                    </div>
                                    <div class="form-group mr-2">
                                        <small class="text-muted d-block mb-2 hide-text">PREFERRED</small>
                                        <label for="txtPreferredExamLocation-${index}">Exam Location</label>
                                        <input type="text" class="form-control form-control-sm block-dependant"
                                            id="txtPreferredExamLocation-${index}" name="txtPreferredExamLocation-${index}"
                                            placeholder="Exam Location">
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="form-group mr-2">
                                        <small class="text-muted d-block mb-2">ALTERNATIVE</small>
                                        <label for="dtpAlternativeExamDate-${index}">Exam Date</label>
                                        <input type="text" class="form-control form-control-sm block-dependant date-control"
                                            id="dtpAlternativeExamDate-${index}" name="dtpAlternativeExamDate-${index}"
                                            placeholder="DD/MM/YYYY" data-parsley-dateformat>
                                    </div>
                                    <div class="form-group">
                                        <small class="text-muted d-block mb-2 hide-text">ALTERNATIVE</small>
                                        <label for="txtAlternativeExamLocation-${index}">Exam Location</label>
                                        <input type="text" class="form-control form-control-sm block-dependant"
                                            id="txtAlternativeExamLocation-${index}" name="txtAlternativeExamLocation-${index}"
                                            placeholder="Exam Location">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 d-flex justify-content-end align-items-baseline c-exam-list__item-total">
                            <div class="c-exam-list__currency float-right">NGN</div>
                            <span class="c-exam-list__amount float-right"></span>
                        </div>
                    </div>
                </div>
                
            `;
        });

        purchaseListWrapperEl.innerHTML = html;
    }
    updateErrorClass(el: HTMLElement, shouldAdd: boolean) {
        const isInput = this.config.usedInputs.indexOf(el.tagName.toUpperCase()) >= 0;
        let className = isInput ? "is-invalid" : "c-exam-list__error";
        
        className = !isInput && el.hasAttribute('data-parsley-radio-required') ? 'radio-control__error' : className;

        if (shouldAdd) {
            el.classList.add(className);
        } else {
            el.classList.remove(className);
        }

        // if (className === 'c-exam-list__error')
    }
    calcItemTotal(examName: string, servicePrice: number, add: boolean): number {
        // get item total
        let total: number[] = this.itemTotal.get(examName) || [];

        // add or subtract from item total
        if (add) {
            total.push(servicePrice);
        } else {
            total.splice(total.indexOf(servicePrice), 1);
        }

        // set the updated value to map
        this.itemTotal.set(examName, total);

        return total.reduce((x, y) => x + y, 0);
    }
    calcPurchaseTotal(): number {
        let totalPayable: number = 0;
        Array.from(this.itemTotal.values() || []).forEach((lineAbout) => {
            totalPayable += lineAbout.reduce((x1, x2) => x1 + x2, 0);
        });

        return totalPayable;
    }
    updateItemTotal(listItemEl: HTMLElement, total: number) {
        const totalEl = listItemEl.querySelector(`.${this.config.examItemTotalClass}`)!;

        let totalPayable: number = this.calcPurchaseTotal();

        totalEl.innerHTML = TeboUtility.numberFormatter(String(total), 2, '.', ',');
        this.payableTotalEl.innerHTML = TeboUtility.numberFormatter(String(totalPayable), 2, '.', ',');
    }

    toggleButtonsVisibility(sectionIndex: number) {
        // sectionIndex is the section we are heading to
        this.prevButtonEl.classList.toggle("invisible", sectionIndex === 0);
        this.nextButtonEl.classList.toggle("invisible", sectionIndex === this.lastSectionIndex);
        // this.submitButtonEl.classList.toggle("d-none", sectionIndex < this.lastSectionIndex);
    }

    sampleData() {
        return [
            {
                "emailAddress": "example@gmail",
                "total": 3000,
                "purchases": [
                    {
                        "examId": 1,
                        "examName": "toefl",
                        "selectedServices": [
                            "exam-registration",
                            "book-purchase",
                            "lesson-enrolment"
                        ],
                        "preferredExamDate": "2020-12-30",
                        "preferredExamLocation": "Location 1",
                        "alternativeExamDate": "2020-12-30",
                        "alternativeExamLocation": "Location 2",
                        "examRegistrationPrice": 1000,
                        "bookPurchasePrice": 1000,
                        "lessonEnrolmentPrice": 1000,
                        "itemTotal": 3000
                    }
                ]
            },
            {
                "title": "Mr",
                "lastName": "John",
                "firstName": "Doe",
                "otherName": "",
                "gender": "1",
                "birthDate": "2020-11-27",
                "firstLanguage": "Hausa",
                "country": "Nigeria",
                "state": "Abia",
                "phoneNumber": "09038843321",
                "passportNumber": "dfadfafdsfd",
                "passportExpiryDate": "2020-11-30",
                "permanentAddress": "dfafsafdsafdsaf",
                "currentLevelOfStudy": "dfasdfdsfasfdsf",
                "nextLevelOfStudy": "dsfsafsfdsafdsf"
            },
            {
                "lastName": "Ibrahim",
                "firstName": "Sadiq",
                "country": "Nigeria",
                "state": "Benue",
                "educationalBackground": "dfafdsaf",
                "occupation": "dfaffasfds",
                "currentPosition": "dfasfdfa",
                "officeAddress": "dfafdasfdsfds",
                "emailAddress": "example@gmail.com",
                "phoneNumber": "09034544332"
            }
        ];
    }

    sampleAction(e) {
        this.saveApplication(this.sampleData())
            .then(result => {
                console.log('inside then', result);
            })
            .catch(error => {

                console.log('error', error);
            });

    }

    async saveApplication(applicationData) {
        const data = new FormData();
        const url = frontend_script_config.ajaxRequestUrl;

        data.append("action", "saveApplication");
        data.append("nonce", frontend_script_config.saveApplicationNonce);
        data.append("data", JSON.stringify(applicationData));

        const response = await fetch(url, {
            method: "POST",
            body: new URLSearchParams(data as URLSearchParams)
        });

        const result = await response.json();

        console.log('result', result);

        if (response.status !== 200)
            throw new Error("Unable to save application");

        return result;
    }

    handleNextSection(sectionIndex: number) {
        // validate current section
        const isSectionValid = this.validateSection(this.currentSectionIndex);

        if (!isSectionValid) {
            // this.indicateInvalidControls(this.currentSectionIndex);
            return;
        }

        this.collectData(this.currentSectionIndex);

        this.saveApplication(this.wizardData)
            .then(result => {
                console.log('inside then', result);
            })
            .catch(error => {
                console.log('error', error);
            });



        console.log(this.wizardData);

        this.toggleButtonsVisibility(sectionIndex);

        // display the section
        this.markSectionCompleted(this.currentSectionIndex);
        this.markSectionCompleted(sectionIndex, false);
        this.enableSection(sectionIndex);
        this.showSection(sectionIndex);

        this.currentSectionIndex = sectionIndex;
    }

    handlePrevSection(sectionIndex: number) {
        this.toggleButtonsVisibility(sectionIndex);

        this.markSectionCompleted(sectionIndex, false);
        this.showSection(sectionIndex);
        this.currentSectionIndex = sectionIndex;
    }

    getNextSectionIndex(): number {
        if ((this.currentSectionIndex + 1) >= this.lastSectionIndex) return this.lastSectionIndex;
        return this.currentSectionIndex + 1;
    }

    getPrevSectionIndex(): number {
        if ((this.currentSectionIndex - 1) <= 0) return 0;
        return this.currentSectionIndex - 1;
    }

    enableSection(sectionIndex: number) {
        this.tabEls[sectionIndex].classList.remove(this.config.tabDisabledClass);
    }

    disableSection(sectionIndex: number) {
        this.tabEls[sectionIndex].classList.add(this.config.tabDisabledClass);
    }

    showSection(sectionIndex: number) {
        // find the section by section index
        const sectionEl = this.sectionEls[sectionIndex];
        const tabEl = this.tabEls[sectionIndex];

        if (!sectionEl || !tabEl) return;

        // deactivate all tab + section
        this.removeClassFrom(this.tabEls, this.config.tabActiveClass);
        this.removeClassFrom(this.sectionEls, this.config.sectionActiveClass);

        // activate the tab + section
        tabEl?.classList.add(this.config.tabActiveClass);
        sectionEl?.classList.add(this.config.sectionActiveClass);

        // set the current index
        this.currentSectionIndex = sectionIndex;
    }

    markSectionCompleted(sectionIndex: number, status: boolean = true) {
        // find the section by section index
        const tabEl = this.tabEls[sectionIndex];

        (status) ? tabEl?.classList.add(this.config.tabCompletedClass)
                 : tabEl?.classList.remove(this.config.tabCompletedClass);
    }

    validateSection(sectionIndex: number): boolean {
        let outcome = this.validationInstance.validate({
            group: "block-" + sectionIndex,
            force: true
        });

        return outcome === true;
    }

    removeClassFrom(els: HTMLElement[], className: string) {
        els.forEach(el => {
            el?.classList.remove(className);
        });
    }

    registerCustomValidators() {
        Parsley.addValidator('dateformat', {
            validate: function (value, requirement, instance) {
                var isValid = moment(value, "DD/MM/YYYY", true).isValid();
                // return isValid;
                return true;
            },
            messages: {
                en: 'Please provide date in format DD/MM/YYYY'
            }
        })

        Parsley.addValidator('radioRequired', {
            messages: { en: "This value is required" },
            validateString: (value, requirement, instance) => {
                const el: HTMLElement = instance.element as HTMLElement;

                return el.querySelectorAll(`input[name=${requirement}]:checked`).length > 0;
            }
        });

        Parsley.addValidator('requireIf', {
            messages: { en: 'You must specify your preferred and alternative exam date and location' },
            validateString: (value, requirement, instance) => {
                if (!document.querySelector(requirement)?.checked)
                    return true;

                const itemParentEl = TeboUtility.getClosest(instance.element, `[${this.config.examItemIndexAttr}]`)! as HTMLElement;
                const examIndex = itemParentEl.getAttribute(this.config.examItemIndexAttr);

                return instance.parent.isValid({ group: 'child-block-' + examIndex, force: true });
            }
        });

        Parsley.addValidator('minPurchase', {
            messages: { en: "You have to purchase at least one service" },
            validateString: (value, requrement, instance) => {
                const oneServiceSelected = Array.from(document.querySelectorAll(`[${this.config.examItemAttr}]`)).some(sectionEl => {
                    return Array.from(sectionEl.querySelectorAll(`.${this.config.serviceMarkerClass}`)).some(input => (input as HTMLInputElement)?.checked);
                });

                return oneServiceSelected;
            }
        });
    }

    getExamServiceName(examService: ExamServiceEnum): string {
        if (examService === ExamServiceEnum.ExamRegistration)
            return 'Exam Registration';

        if (examService === ExamServiceEnum.BookPurchase)
            return 'Book';

        if (examService === ExamServiceEnum.LessonEnrollment)
            return 'Lesson';

        return '';
    }

    collectData(sectionIndex) {
        if (sectionIndex < 0 || sectionIndex >= this.lastSectionIndex)
            return;
        
        this.wizardData[sectionIndex] = sectionIndex === 0 ? this.collectPurchaseData() :
                                        sectionIndex === 1 ? this.collectPersonalData() : 
                                        sectionIndex === 2 ? this.collectGuardianData() : 
                                        null;
    }

    collectPurchaseData(): IPurchaseRequest {
        let totalPurchase: number = 0;
        const request: IPurchaseRequest = {
            emailAddress: (document.getElementById('txtEmail')! as HTMLInputElement).value,
            total: 0,
            purchases: []
        };
        
        this.sectionEls[0].querySelectorAll(`[${this.config.examItemAttr}]`).forEach((listItemEl, listItemIndex) => {
            const result = {};
            const selectedServices: string[] = [];
            // const examIndex: number = +listItemEl.getAttribute(this.config.examItemIndexAttr)!;

            listItemEl.querySelectorAll("input[data-service]:checked").forEach(el => {
                return selectedServices.push(el.getAttribute('data-service')!);
            });

            if (selectedServices.length === 0) return;
            
            const examIndex = +(listItemEl.getAttribute(`${this.config.examItemIndexAttr}`) || -1);
            const servicePrices = this.config.supportedExams[examIndex].servicePrices;

            result['examId'] = +listItemEl.getAttribute(this.config.examItemIdAttr)!;
            result['examName'] = listItemEl.getAttribute(this.config.examItemAttr)!;
            result['selectedServices'] = selectedServices;
            result['preferredExamDate'] = (listItemEl.querySelector(`[id^='dtpPreferredExamDate']`) as HTMLInputElement)?.value; //#-${examIndex}
            result['preferredExamLocation'] = (listItemEl.querySelector(`[id^='txtPreferredExamLocation']`) as HTMLInputElement)?.value;
            result['alternativeExamDate'] = (listItemEl.querySelector(`[id^='dtpAlternativeExamDate']`) as HTMLInputElement)?.value;
            result['alternativeExamLocation'] = (listItemEl.querySelector(`[id^='txtAlternativeExamLocation']`) as HTMLInputElement)?.value;
            result['examRegistrationPrice'] = servicePrices.get(ExamServiceEnum.ExamRegistration)!;
            result['bookPurchasePrice'] = servicePrices.get(ExamServiceEnum.BookPurchase)!;
            result['lessonEnrolmentPrice'] = servicePrices.get(ExamServiceEnum.LessonEnrollment)!;
            result['itemTotal'] = this.calcPurchaseTotal();

            request.total += result['itemTotal'];

            request.purchases.push(result as IPurchaseItem);
        });

        console.log('outcome - purchase - ', request);
        
        return request;
    }

    collectPersonalData(): IPersonalData {
        const countryEl: HTMLSelectElement = document.querySelector('#ddlPersonalCountry')! as HTMLSelectElement;
        const stateEl: HTMLSelectElement = document.querySelector('#ddlPersonalState')! as HTMLSelectElement;

        const result = {
            title: (document.querySelector('input[name=rbPersonalTitle]:checked')! as HTMLInputElement)?.value || '',
            lastName: (document.querySelector('#txtPersonalLastName')! as HTMLInputElement).value || '',
            firstName: (document.querySelector('#txtPersonalFirstName')! as HTMLInputElement).value || '',
            otherName: (document.querySelector('#txtPersonalOtherName')! as HTMLInputElement).value || '',
            gender: (document.querySelector('input[name=rbPersonalGender]:checked')! as HTMLInputElement)?.value || '',
            birthDate: (document.querySelector('#dtpPersonalDob')! as HTMLInputElement).value || '',
            firstLanguage: (document.querySelector('#txtPersonalFirstLanguage')! as HTMLInputElement).value || '',
            country: countryEl.options[countryEl.selectedIndex]?.text || '',
            state: stateEl.options[stateEl.selectedIndex]?.text || '',
            phoneNumber: (document.querySelector('#txtPersonalPhoneNumber')! as HTMLInputElement).value || '',
            passportNumber: (document.querySelector('#txtPersonalPassportNumber')! as HTMLInputElement).value || '',
            passportExpiryDate: (document.querySelector('#dtpPersonalExpiryDate')! as HTMLInputElement).value || '',
            permanentAddress: (document.querySelector('#txtPersonalPermanentAddress')! as HTMLInputElement).value || '',
            currentLevelOfStudy: (document.querySelector('#txtPersonalCurrentLevelOfStudy')! as HTMLInputElement).value || '',
            nextLevelOfStudy: (document.querySelector('#txtPersonalNextLevelOfStudy')! as HTMLInputElement).value || '',
        }

        console.log('outcome - personal - ', result);

        return result;
    }

    collectGuardianData(): IGuardianData {
        const countryEl: HTMLSelectElement = document.querySelector('#ddlGuardianCountry')! as HTMLSelectElement;
        const stateEl: HTMLSelectElement = document.querySelector('#ddlGuardianState')! as HTMLSelectElement;

        const result = {
            lastName: (document.querySelector('#txtGuardianLastName')! as HTMLInputElement).value || '',
            firstName: (document.querySelector('#txtGuardianFirstName')! as HTMLInputElement).value || '',
            country: countryEl.options[countryEl.selectedIndex]?.text || '',
            state: stateEl.options[stateEl.selectedIndex]?.text || '',
            educationalBackground: (document.querySelector('#txtGuardianEducationalBackground')! as HTMLInputElement).value || '',
            occupation: (document.querySelector('#txtGuardianOccupation')! as HTMLInputElement).value || '',
            currentPosition: (document.querySelector('#txtGuardianCurrentPosition')! as HTMLInputElement).value || '',
            officeAddress: (document.querySelector('#txtGuardianOfficeAddress')! as HTMLInputElement).value || '',
            emailAddress: (document.querySelector('#txtGuardianEmailAddress')! as HTMLInputElement).value || '',
            phoneNumber: (document.querySelector('#txtGuardianPhoneNumber')! as HTMLInputElement).value || ''
        }

        console.log('outcome - guardian - ', result);

        return result;
    }
}