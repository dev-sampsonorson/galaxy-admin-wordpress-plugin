import { AsyncResource } from "async_hooks";

export default interface ValidationArg {
    success: boolean;
    key: string;
    message: string;
    successResult: any;
    errorResult: object[];
}