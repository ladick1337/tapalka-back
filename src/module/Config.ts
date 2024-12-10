import * as dotenv from 'dotenv';

dotenv.config();

interface ProjectEnv
{
	DB_NAME : string,
	DB_LOGIN : string,
	DB_PASSWORD : string,
	DB_HOST : string,
	DB_PORT : number,
	DB_DIALECT : 'mysql' | 'postgres' | 'mariadb' | 'sqlite',
	DB_LOGGING : boolean,
	DEBUG : boolean,
	HTTP_PORT : number
}

const Config : ProjectEnv = {
	DB_NAME : process.env.DB_NAME!,
	DB_LOGIN : process.env.DB_LOGIN!,
	DB_PASSWORD : process.env.DB_PASSWORD!,
	DB_HOST : process.env.DB_HOST!,
	DB_PORT : +process.env.DB_PORT!,
	DB_DIALECT : <'mysql' | 'postgres' | 'mariadb' | 'sqlite'>process.env.DB_DIALECT,
	DB_LOGGING : !!+process.env.DB_LOGGIN!,
	DEBUG : !!+process.env.DEBUG!,
	HTTP_PORT : +process.env.HTTP_PORT!
};

export default Config;