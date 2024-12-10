import Controller from "./Controller";
import {HttpAppAuthorizedRequest} from "../HttpAppRequest";
import Asset from "../../models/Asset";
import {Validator} from "js-simple-validator";
import HttpAppError from "../HttpAppError";
import {Op, Sequelize} from "sequelize";
import NoRightsException from "../exceptions/NoRightsException";
import ApiResponse from "../responses/ApiResponse";

export default class AssetsController extends Controller
{


	public async sell(req : HttpAppAuthorizedRequest) : Promise<ApiResponse<{}>>
	{

		let {quantity} = Validator.make({
			quantity : Validator.rule.setDefault(1).isInt().range(1, 1000)
		}).validate(req.body);

		let asset = await this.getBind('asset', Asset);

		if(asset.user_id !== req.user.id){
			throw new NoRightsException;
		}

		let item = await asset.getItem();

		await this.db.transaction(async transaction => {

			let [updatedRows] = await Asset.update({
				quantity : Sequelize.literal(`quantity - ${quantity}`)
			}, {
				where : {
					id : asset.id,
					quantity : {
						[Op.gte] : quantity
					}
				}
			});

			if(!updatedRows){
				throw new HttpAppError('Dont have so many assets', 422);
			}

			await Asset.destroy({
				where : {
					id : asset.id,
					quantity : 0
				}
			})

			//Начисляем баланс
			await req.user.restoreBalance(item.price * quantity, {transaction});

		});

		return new ApiResponse({}, 204);

	}

}