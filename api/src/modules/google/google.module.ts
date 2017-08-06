import { Module } from 'nest.js';

import { GoogleController } from './google.controller';
import { GoogleService } from './google.service';

@Module({
    controllers: [GoogleController],
    components: [GoogleService]
})
export class GoogleModule { }