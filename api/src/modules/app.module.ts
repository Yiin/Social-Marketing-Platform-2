import { Module } from 'nest.js';

import { GoogleModule } from './google/google.module';

@Module({
    modules: [GoogleModule]
})
export class ApplicationModule { }