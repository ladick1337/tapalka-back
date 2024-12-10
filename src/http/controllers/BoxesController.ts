import Controller from "./Controller";
import HttpAppRequest, {HttpAppAuthorizedRequest} from "../HttpAppRequest";
import Box from "../../models/Box";
import Paginator from "../../module/Paginator/Paginator";
import ApiResponse from "../responses/ApiResponse";
import Item from "../../models/Item";
import ApiPaginationResponse from "../responses/ApiPaginationResponse";

export default class BoxesController extends Controller
{

	public async list(req : HttpAppRequest) : Promise<ApiPaginationResponse<typeof Box>>
	{

		let paginator = new Paginator(Box);

		paginator.setSortFields(['id', 'name', 'created_at', 'price']);

		return new ApiPaginationResponse(
			await paginator.build(req, () => {
				return {}
			}),
			200
		);

	}

	public async box(req : HttpAppRequest) : Promise<ApiResponse<Box>>
	{

		let box = await this.getBind('box', Box);

		return new ApiResponse(box, 200);

	}

	public async items(req : HttpAppRequest) : Promise<ApiResponse<Item[]>>
	{

		let box = await this.getBind('box', Box);

		let items = await box.getItems();

		return new ApiResponse(items);

	}

	public async open(req : HttpAppAuthorizedRequest){



	}

}