import * as dotenv from 'dotenv';

dotenv.config();

interface ProjectEnv extends process.env
{
	DB_NAME : string,
	DB_LOGIN : string,
	DB_PASSWORD : string,
	DB_HOST : string,
	DB_PORT : string,
	DB_DIALECT : 'mysql' | 'postgres' | 'mariadb' | 'sqlite',
	DB_LOGGING : '1' | '0',
	DEBUG : '1' | '0',
	HTTP_PORT : string
}

export const Env : ProjectEnv = process.env;