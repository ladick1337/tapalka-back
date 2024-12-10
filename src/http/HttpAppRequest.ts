import {Request} from "express";
import User from "../models/User";
import Token from "../models/Token";

export default interface HttpAppRequest extends Request{
}

export interface HttpAppAuthorizedRequest extends HttpAppRequest
{
	user : User,
	token : Token
}