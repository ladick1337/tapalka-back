import {NextFunction, Response} from "express";
import HttpAppRequest from "../HttpAppRequest";
import {ValidatorError} from "js-simple-validator";
import HttpAppError from "../HttpAppError";

export default (err : any, req : HttpAppRequest, res : Response, next : NextFunction) => {

	next(
		err instanceof ValidatorError
			? new HttpAppError(`'${err.field}' error: ${err.message}`, 422)
			: err
	);

}