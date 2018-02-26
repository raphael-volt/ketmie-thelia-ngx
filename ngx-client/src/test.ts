// This file is required by karma.conf.js and loads recursively all the .spec and framework files

import 'zone.js/dist/zone-testing';
import { getTestBed } from '@angular/core/testing';
import {
  BrowserDynamicTestingModule,
  platformBrowserDynamicTesting
} from '@angular/platform-browser-dynamic/testing';

declare const require: any;

// First, initialize the Angular testing environment.
getTestBed().initTestEnvironment(
  BrowserDynamicTestingModule,
  platformBrowserDynamicTesting()
);
var context
// Then we find all the tests.
context = require.context('./', true, /card\.service\.spec\.ts$/);
// And load the modules.
context.keys().map(context);
context = require.context('../packages/thelia', true, /\.spec\.ts$/);
context.keys().map(context);
