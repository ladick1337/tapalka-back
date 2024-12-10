import PaginatorResponse from "../../module/Paginator/PaginatorResponse";
import {Model} from "sequelize";
import ApiResponse from "./ApiResponse";

export default class ApiPaginationResponse<T extends typeof Model> extends ApiResponse
{

	public constructor(paginatorResponse : PaginatorResponse<T>, code : number = 200) {

		super({
			items : paginatorResponse.items,
			count : paginatorResponse.count,
			pages : paginatorResponse.lastPage
		}, code);

	}


}