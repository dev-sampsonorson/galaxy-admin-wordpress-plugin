interface Element {
    matchesSelector(selectors: string): boolean;
    mozMatchesSelector(selectors: string): boolean;
    msMatchesSelector(selectors: string): boolean;
    oMatchesSelector(selectors: string): boolean;
}

interface JQuery {
    parsley(options?: any): any;
    datepicker(options?: any): any;
}


declare const frontend_script_config: {
    publicKey: string;
    ajaxRequestUrl: string;
    getExamListNonce: string;
    saveApplicationNonce: string;
    generatePaymentRefNonce: string;
    verifyTransactionNonce: string;
};

declare const PaystackPop: any;