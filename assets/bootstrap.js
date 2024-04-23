import { startStimulusApp } from '@symfony/stimulus-bundle';
import Popover from 'stimulus-popover';
import * as Turbo from '@hotwired/turbo';


const app = startStimulusApp();
// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);

app.register('popover', Popover);

// Turbo.session.drive = false
