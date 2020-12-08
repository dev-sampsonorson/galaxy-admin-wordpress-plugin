import ValidationArg from "./ValidationArg";

export default class ValidationError extends Error {
    private args: ValidationArg;

    constructor(message, args: ValidationArg) {
        super(message);
        this.name = "ValidationError";
        this.args = args;
    }

    public getArgs() {
        return this.args;
    }
}