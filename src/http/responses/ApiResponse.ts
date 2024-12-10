import HttpAppResponse from "../HttpAppResponse";
import {Response} from "express";

interface JsonResponse extends Record<string, any>
{

}

export default class ApiResponse<T = any> extends HttpAppResponse
{

	protected response : JsonResponse;
	protected code : number;

	public constructor(response : JsonResponse, code : number = 200) {
		super();
		this.response = response;
		this.code = code;
	}

	public handle(res: Response) {
		res.json(this.response).status(this.code);
	}


}