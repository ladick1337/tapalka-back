import HttpAppRequest from "../HttpAppRequest";
import {NextFunction, Response} from "express";
import Item from "../../models/Item";

export default (...permissions : string[]) => (req : HttpAppRequest, res : Response, next : NextFunction) => {

	if(('admin' in req)){

		let admin = <Item>req.admin;

		if(permissions.every(p => admin.role!.permissions.includes(p))){
			return next();
		}

	}

	res.json({
		status : false,
		error : 'Permissions error',
		code : 403
	});

}