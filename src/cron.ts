// import RestoreEnergyTask from "./cron/RestoreEnergyTask";
// import {Config} from "./module/Config";
// import {connectDatabase} from "./db";
// import {Config} from "./config";
//
// Config.init();
//
// let db = connectDatabase();
//
// function create<T extends new (db: any) => InstanceType<T>>(t: T): InstanceType<T> {
// 	return new t(db) as InstanceType<T>;
// }
//
// create(RestoreEnergyTask).setRepeatTime(Config.Energy.uptime.interval).watchBackground();
