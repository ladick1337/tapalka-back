import {NextFunction, Response} from "express";
import HttpAppRequest from "../HttpAppRequest";
import HttpAppError from "../HttpAppError";
import {ValidatorError} from "js-simple-validator";

export default (err : any, req : HttpAppRequest, res : Response, next : NextFunction) => {

	if(err instanceof HttpAppError) {
		res.status(err.code).json({
			error: err.message
		});

	}else if(err instanceof ValidatorError){
		res.status(422).json({
			error : err.field + ' field error: ' + err.message
		});
	}else{

		res.status(500).json({
			error : 'Server error'
		});

		console.log('==========');
		console.error(err);
		console.log('==========');

	}


}