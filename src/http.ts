import express from "express";

import {connectDatabase} from "./db";
import HttpApp from "./http/HttpApp";
import Config from "./module/Config";

let db = connectDatabase();
let server = express();

let controller = new HttpApp(server, db),
	port = Config.HTTP_PORT;

console.log('Http started', port);
controller.start(port);