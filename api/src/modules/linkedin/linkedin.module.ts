import { Module } from 'nest.js';

import { LinkedinController } from './linkedin.controller';

@Module({
    controllers: [LinkedinController]
})
export class LinkedinModule { }