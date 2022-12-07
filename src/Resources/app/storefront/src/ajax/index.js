import Plugin from 'src/plugin-system/plugin.class';
import HttpClient from 'src/service/http-client.service'
import LoadingIndicator from 'src/utility/loading-indicator/loading-indicator.util';

export default class AjaxPlugin extends Plugin {
    init() {
        this.innerHtml = LoadingIndicator.getTemplate();
        this.client = new HttpClient(window.accessKey)
        this.fetch()
    }
    fetch() {
        this.client.get('/mcx/example-json', (responseText) => {
            const responseData = JSON.parse(responseText);
            this.el.innerHTML = responseData.foo
        })
    }
}