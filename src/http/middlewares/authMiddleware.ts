import HttpAppRequest, {HttpAppAuthorizedRequest} from "../HttpAppRequest";
import {NextFunction, Response} from "express";
import User from "../../models/User";
import Token from "../../models/Token";
import NeedAuthException from "../exceptions/NeedAuthException";

export default async (req : HttpAppRequest, res : Response, next : NextFunction) => {

	let token = req.header('Authorization')?.split(' ').pop();

	console.log(req.headers);

	if(token && token.length >= 1 && token.length <= 255){

		let auth = await Token.findOne({
			where : { token },
			include : User
		});

		if(auth){
			(req as HttpAppAuthorizedRequest).user = auth.user!;
			(req as HttpAppAuthorizedRequest).token = auth!;

			return next();
		}

	}

	return next(new NeedAuthException);

}