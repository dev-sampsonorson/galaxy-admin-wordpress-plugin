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
    ajaxRequestUrl: string;
    getExamListNonce: string;
    saveApplicationNonce: string;
};