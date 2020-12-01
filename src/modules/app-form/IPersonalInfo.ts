export default interface IPersonalInfo {
    titleId?: number;
    lastName: string;
    firstName: string;
    otherNames: string;
    gender?: number;
    countryId?: number;
    stateOfOrigin?: number;
    dateOfBirth?: Date;

    passportNumber?: string;
    expiryDate?: Date;

    permanentAddress?: string;
    phoneNumber?: string;
    emailAddress?: string;

    firstLanguage?: string;
    currentLevelOfStudy?: string;
    nextLevelOfStudy?: string;
}