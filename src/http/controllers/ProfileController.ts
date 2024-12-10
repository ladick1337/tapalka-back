import Controller from "./Controller";
import {HttpAppAuthorizedRequest} from "../HttpAppRequest";
import User from "../../models/User";
import ApiResponse from "../responses/ApiResponse";
import Asset from "../../models/Asset";
import Paginator from "../../module/Paginator/Paginator";
import Item from "../../models/Item";
import ApiPaginationResponse from "../responses/ApiPaginationResponse";

export default class ProfileController extends Controller
{

	/**
	 * Получение информации о текущем пользователе
	 */
	public async me(req : HttpAppAuthorizedRequest) : Promise<ApiResponse<User>>
	{
		return new ApiResponse(req.user, 200);
	}

	/**
	 * Удаление токена
	 */
	public async logout(req : HttpAppAuthorizedRequest) : Promise<ApiResponse<{}>>
	{
		await req.token.destroy();

		return new ApiResponse({}, 204);
	}

	/**
	 * Список вещей
	 */
	public async assets(req : HttpAppAuthorizedRequest) : Promise<ApiPaginationResponse<typeof Asset>>
	{

		let paginator = new Paginator(Asset);

		paginator.setSortFields(['id', 'created_at', 'item_id']);

		return new ApiPaginationResponse(
			await paginator.build(req, () => {
				return {
					where : {
						user_id : req.user.id
					},
					include : Item
				}
			}),
			200
		)

	}

}