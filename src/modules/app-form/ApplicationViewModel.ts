import ko from 'knockout';
import IGuardianInfo from './IGuardianInfo';
import IPersonalInfo from './IPersonalInfo';

export default class ApplicationViewModel {

    personalInfo: IPersonalInfo;
    guardianInfo: IGuardianInfo;


    constructor() {
        this.personalInfo = {
            lastName: "Peter",
            firstName: "John",
            otherNames: "Frank"
        };

        this.guardianInfo = {};
    }
}