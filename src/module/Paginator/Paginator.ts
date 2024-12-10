import {Model} from "sequelize";
import {Attributes, FindOptions} from "sequelize/types/model";
import {Request} from "express";
import {Validator} from "js-simple-validator";
import PaginatorResponse from "./PaginatorResponse";

interface PaginatorOptionsCallback<T extends typeof Model>{
	(req : Request) : FindOptions<Attributes<InstanceType<T>>>;
}

type PaginatorSortField<T extends typeof Model> =
	Attributes<InstanceType<T>>;

export default class Paginator<T extends typeof Model>
{

	protected model : T;
	protected sortFields : PaginatorSortField<T>[] = ['id'];

	public constructor(
		model : T
	) {
		this.model = model;
	}

	public setSortFields(fields : PaginatorSortField<T>[]) : this
	{
		this.sortFields = fields;
		return this;
	}

	public async build(request : Request, optionsGenerator : PaginatorOptionsCallback<T>) : Promise<PaginatorResponse<T>>
	{

		let options = optionsGenerator(request);

		if(!('where' in options)){
			options.where = {}
		}

		let {page, limit, sortField, sortMode} = Validator.make({
			page : Validator.rule.setDefault(1).isInt().min(1).max(99999999),
			limit : Validator.rule.setDefault(100).isInt().min(1).max(100),
			sortField : Validator.rule.setDefault('id').isString().in(this.sortFields),
			sortMode : Validator.rule.setDefault('desc').isString().in(['asc', 'desc'])
		}).validate({...request.body, ...request.query});

		options.order = [[sortField, sortMode]];

		return await PaginatorResponse.query(this.model, limit, page, options);

	}

}