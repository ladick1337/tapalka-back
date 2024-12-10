import Controller from "./Controller";
import HttpAppRequest from "../HttpAppRequest";
import User from "../../models/User";
import {Validator} from "js-simple-validator";
import Web3 from "web3";
import HttpAppError from "../HttpAppError";
import Token from "../../models/Token";
import {v4 as uuidv4} from 'uuid';
import ApiResponse from "../responses/ApiResponse";

export default class AuthController extends Controller
{

	protected getTimestamp() : number
	{
		return Math.floor(
			Date.now() / 1000
		);
	}

	public async nonce(req : HttpAppRequest) : Promise<ApiResponse<{ nonce : string }>>
	{
		return new ApiResponse({
			nonce : this.getTimestamp()
		});
	}

	public async verify(req : HttpAppRequest) : Promise<ApiResponse<Token>>
	{

		let now = this.getTimestamp();

		let {signature, nonce, address, ref_code} = Validator.make({
			signature : Validator.rule.setRequired().isString().length(1, 255),
			nonce : Validator.rule.setRequired().isInt().min(1),
			address : Validator.rule.setRequired().isString().length(1, 255).custom(address => address.toLowerCase()),
			ref_code : Validator.rule.isString().length(1, 255)
		}).validate(req.body);

		if(nonce < now - 60 || nonce > now + 60){
			throw new HttpAppError('Expired nonce', 422);
		}

		//Проверяем подпись
		try{

			let signer = (new Web3)
				.eth.accounts.recover(nonce.toString(), signature)
				.toLowerCase();

			if(signer !== address){
				throw new HttpAppError('Bad sign', 422);
			}

		}catch (e){
			throw new HttpAppError('Bad sign', 422);
		}

		let token = await this.db.transaction(async (transaction) => {

			let user = await User.findOne({ where : { wallet : address } });

			if(!user){

				let parent = ref_code
					? await User.findOne({ where : { ref_code } })
					: null;

				user = await User.create(
					{
						wallet : address,
						ref_code : uuidv4().substr(0, 8),
						parent_id : parent?.id

					},
					{ transaction }
				);

			}

			return await user.createToken(1000 * 60 * 60 * 24 * 7, { transaction });

		});

		return new ApiResponse(token, 200);

	}

}