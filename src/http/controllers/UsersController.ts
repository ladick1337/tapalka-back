import Controller from "./Controller";
import HttpAppRequest from "../HttpAppRequest";
import User from "../../models/User";
import ApiResponse from "../responses/ApiResponse";
import Asset from "../../models/Asset";
import Paginator from "../../module/Paginator/Paginator";
import Item from "../../models/Item";
import ApiPaginationResponse from "../responses/ApiPaginationResponse";

export default class UsersController extends Controller
{

	public async user(req : HttpAppRequest) : Promise<ApiResponse<User>>
	{

		let user = await this.getBind('user', User);

		return new ApiResponse(user, 200);

	}

	public async assets(req : HttpAppRequest) : Promise<ApiPaginationResponse<typeof Asset>>
	{

		let user = await this.getBind('user', User);

		let paginator = new Paginator(Asset);

		paginator.setSortFields(['id', 'created_at', 'item_id']);

		return new ApiPaginationResponse(
			await paginator.build(req, () => {
				return {
					where : {
						user_id : user.id
					},
					include : Item
				}
			}),
			200
		)

	}

}