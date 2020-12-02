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

    console.log(data);

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


    /* (async function () {

        let data = [
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

        console.log(data);

        const result = await savePurchase(data).catch(handleError);
    })();

    return; */





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

            supportedExams: examList
        });
        
    })();

    // let registration = new ApplicationViewModel();
    // let domContext = document.querySelector(".c-regfrom");

    // const formWizardViewModel = formWizard.getViewModel();
    // ko.applyBindings(this.getViewModel(), this.wrapperEl);

    console.log("DOM fully loaded and parsed");
});