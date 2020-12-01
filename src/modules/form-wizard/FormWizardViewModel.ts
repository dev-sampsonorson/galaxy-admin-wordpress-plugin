import ko from 'knockout';
import TeboUtility from '../../TeboUtility';
import { ExamServiceEnum, IFormWizardViewModel } from './Utility';

export default class FormWizardViewModel {
    /* exams: ko.ObservableArray<IExam>;

    serviceInfo: any[];


    // private serivceList: string[];

    constructor(private config: IFormWizardViewModel) {
        this.exams = ko.observableArray(this.config.exams);

        this.serviceInfo = [
            { code: 'exam-reg', name: 'Exam Registration' },
            { code: 'book', name: 'Book' },
            { code: 'lesson', name: 'Lesson' }
        ]
    }

    getExamServiceCode(serviceIndex: number): string {
        return this.serviceInfo[serviceIndex].code;
    }

    getExamServicePrice(exam: IExam, serviceIndex: number): string {
        const price: number = exam.servicePrices.find(item => item.serviceIndex === serviceIndex)?.servicePrice!;
        return TeboUtility.numberFormatter(String(price), 0, '.', ',');
    } */
}