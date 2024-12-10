import {Express, NextFunction, Response, Router} from "express";
import fileUpload from 'express-fileupload';
import bodyParser from "body-parser";
import errorsMiddleware from "./middlewares/errorsMiddleware";
import HttpAppError from "./HttpAppError";
import Controller from "./controllers/Controller";
import HttpAppRequest from "./HttpAppRequest";
import {Sequelize} from "sequelize";
import HttpAppResponse from "./HttpAppResponse";
import cors from 'cors';
import AuthController from "./controllers/AuthController";
import ProfileController from "./controllers/ProfileController";
import authMiddleware from "./middlewares/authMiddleware";
import AssetsController from "./controllers/AssetsController";
import BoxesController from "./controllers/BoxesController";
import UsersController from "./controllers/UsersController";
import NotFoundException from "./exceptions/NotFoundException";

export default class HttpApp {

	protected express : Express;
	protected db : Sequelize;

	public constructor(express : Express, db : Sequelize) {
		this.express = express;
		this.db = db;
	}

	/**
	 * Запускает сервер
	 */
	public start(port : number) : void
	{

		this.setAppMiddlewares();
		this.setAppRoutes();
		this.setAppErrorHandlers();

		this.express.listen(port);

	}

	/**
	 * Устанавливает роуты
	 */
	protected setAppRoutes() : void
	{

		////////////////

		let api = Router();

		//Авторизация
		api.get('/auth/nonce', this.callAction(AuthController, 'nonce'));
		api.post('/auth/verify', this.callAction(AuthController, 'verify'));

		//Получение данных текущего юзера
		api.get('/profile/me', authMiddleware, this.callAction(ProfileController, 'me'));

		//Отзыв токена
		api.delete('/profile/logout',authMiddleware, this.callAction(ProfileController, 'logout'));

		//Получение данных текущего юзера
		api.get('/profile/assets', authMiddleware, this.callAction(ProfileController, 'assets'));

		//Продажа предметов
		api.delete('/assets/:asset/sell', authMiddleware, this.callAction(AssetsController, 'sell'));

		//Получение информации юзера
		api.get('/users/:user', this.callAction(UsersController, 'user'));

		//Получение предметов другого пользователя
		api.get('/users/:user/assets', this.callAction(UsersController, 'assets'));

		//Список кейсов
		api.get('/boxes', this.callAction(BoxesController, 'list'));

		//Информация по конкретному кейсу
		api.get('/boxes/:box', this.callAction(BoxesController, 'box'));

		//Вещи в кейсе
		api.get('/boxes/:box/items', this.callAction(BoxesController, 'items'));

		//Открытие кейса
		api.post('/boxes/:box/open', authMiddleware, this.callAction(BoxesController, 'open'));

		api.use((req, res, next) => {
			throw new NotFoundException;
		});

		this.express.use('/api', api);

	}

	/**
	 * Устанавливает обработчики ошибок
	 */
	protected setAppErrorHandlers() : void
	{

		//Errors handler
		this.express.use(errorsMiddleware);

	}

	/**
	 * Устаналивает мидлвейры
	 */
	protected setAppMiddlewares() : void
	{

		//Размер файлов
		this.express.use(fileUpload({
			limits: {
				fileSize : 10 * 1024 * 1024
			},
			limitHandler : () => {
				throw new HttpAppError('File size is very big', 422);
			},
			uploadTimeout : 1000 * 60 * 2
		}));

		//JSON-парсер
		this.express.use(bodyParser.json());

		//GET-парсер
		this.express.use(bodyParser.urlencoded({ extended : true }));

		//CORS
		this.express.use(cors());

		//Static files
		// let expressStatic = express.Router();
		//
		// expressStatic.use(express.static('/storage', {
		// 	setHeaders: (res, path) => {
		// 		if (path.match(/\.(jpg|jpeg|png|gif|ico|css|js|svg|json|atlas|ttf|otf|woff|woff2)$/)) {
		// 			res.setHeader('Cache-Control', 'public, max-age=2592000');
		// 		} else {
		// 			res.setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, proxy-revalidate');
		// 		}
		// 	}
		// }));

	}

	public callAction<T extends Controller>(
		controller : new (...args : any[]) => T,
		method : keyof T
	)  {

		return async (req : HttpAppRequest, res : Response, next : NextFunction) => {

			try{

				let module = new controller(this, this.db, req, res);

				let response = await (module[method] as Function)(req, res, next);

				if(response instanceof HttpAppResponse){
					response.handle(res);
				}else if(response){
					res.send(response.toString());
				}else{
					res.end();
				}

			}catch (e){
				next(e);
			}

		}

	}

}