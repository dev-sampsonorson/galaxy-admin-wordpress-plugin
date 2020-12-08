import $ from 'jquery';
import 'bootstrap';
import './polyfills';
import FormWizard from './modules/form-wizard/FormWizard';
import { Exam, ExamServiceEnum } from './modules/form-wizard/Utility';
import TeboUtility from './TeboUtility';

let handleError = function (err) {
    console.warn(err); 
}

let getAllExams = async function (): Promise<Exam[]> {
    const url = frontend_script_config.ajaxRequestUrl;
    const response = await fetch(url + '?action=getExamList&nonce=' + frontend_script_config.getExamListNonce);

    if (response.status !== 200)
        throw new Error("Exams not found");
    
    const result = (await response.json()).data.result;
    return result.map(item => {
        return new Exam(item.id, item.examName, new Map([
            [ExamServiceEnum.ExamRegistration, +item.examRegistrationPrice],
            [ExamServiceEnum.BookPurchase, +item.bookPurchasePrice],
            [ExamServiceEnum.LessonEnrollment, +item.lessonEnrolmentPrice]])
        );
    });
}

let savePurchase = async function (formData) {
    const data = new FormData();
    const url = frontend_script_config.ajaxRequestUrl;

    /* const data2 = {
        action: "saveApplication",
        nonce: frontend_script_config.saveApplicationNonce,
        data: JSON.stringify(formData)
    }; */

    data.append("action", "saveApplication");
    data.append("nonce", frontend_script_config.saveApplicationNonce);
    data.append("data", JSON.stringify(formData));
    // data.append("data", JSON.stringify(formData));

    // console.log(data);

    // new URLSearchParams(data as URLSearchParams)
    // JSON.stringify(data2)
    const response = await fetch(url, {
        method: "POST",
        body: new URLSearchParams(data as URLSearchParams)
    });

    if (response.status !== 200)
        throw new Error("Unable to save application");
    
    const result = await response.json();

    console.log('ajax result - ', result);



    /* data.append("mediaDataId", mediaDataId + '');
    data.append("action", Action.GetMediaData);
    data.append("nonce", (document.getElementById(Action.GetMediaData + '_nonce')! as HTMLInputElement).value); */



    /*

    let formData = new FormData();

        this.formEl.setAttribute('data-id', mediaDataId + '');

        formData.append("mediaDataId", mediaDataId + '');
        formData.append("action", Action.GetMediaData);
        formData.append("nonce", (document.getElementById(Action.GetMediaData + '_nonce')! as HTMLInputElement).value);

        fetch(this.formEl.dataset.url, {
            method: "POST",
            body: new URLSearchParams(formData as URLSearchParams)
        })



    */
    


    return null
}

window.addEventListener('DOMContentLoaded', (event) => {
    (async function () {

        const studentRegFormEl = document.getElementById('student-reg-form');
        const examList = await getAllExams().catch(handleError) as Exam[];

        if (!studentRegFormEl)
            return;

        if (examList.length === 0)
            studentRegFormEl.style.display = 'none';

        const formWizard = new FormWizard({
            targetId: "student-reg-form",
            tabWrapperSelector: "#student-reg-form .c-wizard-nav",
            purchaseListWrapperSelector: ".c-exam-list .list-group",
            formSelector: "#applicationForm",
            sectionWrapperSelector: "#student-reg-form .c-wizard-form__content",
            sectionNameAttr: "data-section-name",
            sectionIndexAttr: "data-section-index",
            examItemAttr: "data-exam",
            examItemIndexAttr: "data-exam-index",
            examItemIdAttr: "data-exam-id",

            usedInputs: ['INPUT', 'SELECT'],

            defaultSectionIndex: 0,

            tabActiveClass: "c-wizard-nav__step--active",
            tabCompletedClass: "c-wizard-nav__step--completed",
            tabDisabledClass: "c-wizard-nav__step--disabled",

            sectionActiveClass: "c-wizard-form__section--show",

            prevButtonSelector: "#btnPrevSection",
            nextButtonSelector: "#btnNextSection",
            submitButtonSelector: "#btnSubmitApplication",

            serviceMarkerClass: "c-exam-list__service",

            examInfoShowClass: "c-exam-list__reg-detail--show",
            examItemTotalClass: "c-exam-list__amount",
            examPayableTotalSelector: ".c-exam-list__total > .c-exam-list__amount",

            validatableControlSelector: 'input, textarea, select, [data-parsley-require-if], [data-parsley-min-purchase], [data-parsley-radio-required]',

            supportedExams: examList,

            doneMessageDisplaySelector: '[data-section-name="done"] .c-wizard-form__info',
            successMessage: 'Thank you for completing our application form and purchasing our products and services',
            processingMessage: 'Please, wait we are processing your application and payment',

            publicKey: frontend_script_config.publicKey
        });
        
    })();

    // let registration = new ApplicationViewModel();
    // let domContext = document.querySelector(".c-regfrom");

    // const formWizardViewModel = formWizard.getViewModel();
    // ko.applyBindings(this.getViewModel(), this.wrapperEl);

    console.log("DOM fully loaded and parsed");
});