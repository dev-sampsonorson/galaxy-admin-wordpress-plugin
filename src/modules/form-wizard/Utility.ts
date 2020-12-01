import TeboUtility from "../../TeboUtility";

export interface IFormWizardConfig {
    targetId: string;
    tabWrapperSelector: string;
    purchaseListWrapperSelector: string;
    formSelector: string;
    sectionWrapperSelector: string;
    sectionNameAttr: string;
    sectionIndexAttr: string;
    examItemAttr: string;
    examItemIndexAttr: string;
    examItemIdAttr: string;

    usedInputs: string[];

    defaultSectionIndex: number;

    tabActiveClass: string;
    tabCompletedClass: string;
    tabDisabledClass: string;

    sectionActiveClass: string;

    prevButtonSelector: string;
    nextButtonSelector: string;
    submitButtonSelector: string;


    serviceMarkerClass: string;

    examInfoShowClass: string;
    examItemTotalClass: string;
    examPayableTotalSelector: string;

    validatableControlSelector: string;

    supportedExams: Exam[];
}

export interface IFormWizardViewModel {
    supportedExams: Exam[]
}

export class Exam {
    examId: number;
    examName: string;
    servicePrices: Map<ExamServiceEnum, number>;

    constructor(examId: number, examName: string, servicePrices: Map<ExamServiceEnum, number>) {
        this.examId = examId;
        this.examName = examName;
        this.servicePrices = servicePrices;
    }

    getServicePrices(): [ExamServiceEnum, number][]{
        return Array.from(this.servicePrices);
    }

    getServiceName(examService: ExamServiceEnum): string {
        if (examService === ExamServiceEnum.ExamRegistration)
            return 'Exam Registration';
        
        if (examService === ExamServiceEnum.BookPurchase)
            return 'Book';

        if (examService === ExamServiceEnum.LessonEnrollment)
            return 'Lesson';
        
        return '';
    }

    getServicePrice(examService: ExamServiceEnum): string {
        return 'NGN ' + TeboUtility.numberFormatter(this.servicePrices.get(examService), 0, '.', ',');
    }
    onExamServiceChecked(e) {
        console.log(e);
    }
}


export enum ExamServiceEnum {
    ExamRegistration = 'exam-registration',
    BookPurchase = 'book-purchase',
    LessonEnrollment = 'lesson-enrolment'
}

export interface IServicePrice {
    serviceIndex: number;
    servicePrice: number;
}


export interface IPurchaseRequest {
    emailAddress: string;
    total: number;
    purchases: IPurchaseItem[]

}

export interface IPurchaseItem {
    examId: number;
    examName: string;
    selectedServices: string[];
    preferredExamDate: string;
    preferredExamLocation: string;
    alternativeExamDate: string;
    alternativeExamLocation: string;
    examRegistrationPrice: number;
    bookPurchasePrice: number;
    lessonEnrolmentPrice: number;
    itemTotal: number;

}

export interface IPersonalData {
    title: string;
    lastName: string;
    firstName: string;
    otherName: string;
    gender: string;
    birthDate: string;
    firstLanguage: string;
    country: string;
    state: string;
    phoneNumber: string;
    passportNumber: string;
    passportExpiryDate: string;
    permanentAddress: string;
    currentLevelOfStudy: string;
    nextLevelOfStudy: string;
}

export interface IGuardianData {
    lastName: string;
    firstName: string;
    educationalBackground: string;
    occupation: string;
    currentPosition: string;
    officeAddress: string;
    emailAddress: string;
    phoneNumber: string;
}