// Note: this needs to be compiled

import AjaxPlugin from './ajax';

const PluginManager = window.PluginManager
PluginManager.register('AjaxPlugin', AjaxPlugin, '[data-ajax-plugin]')

