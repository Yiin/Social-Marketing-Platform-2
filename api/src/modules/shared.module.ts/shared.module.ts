import { Module } from 'nest.js';

import { SharedService } from './shared.service';

@Module({
    components: [SharedService]
})
export class SharedModule { }