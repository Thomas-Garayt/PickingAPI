// This file is part of the API Platform project.
//
// (c) Kévin Dunglas <dunglas@gmail.com>
//
// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

window.onload = () => {
    const data = JSON.parse(document.getElementById('swagger-data').innerText);

    // We add to the data the Secutiry definition to be able to input the X-Auth-Token.
    // We also remove unrelevant paths like "_profiler".
    data.spec.securityDefinitions = {
        api_key: {
            type: 'apiKey',
            name: 'X-Auth-Token',
            in: 'header'
        }
    };
    
    var tags = [];
    var kPaths = Object.keys(data.spec.paths);
    for(var i=0; i<kPaths.length; i++) {
        var kPath = kPaths[i];
        
        if( kPath.indexOf('_profiler') !== -1 ||
            kPath.indexOf('_error') !== -1 ||
            kPath.indexOf('_wdt') !== -1 ||
            kPath === '/{_locale}/' ||
            kPath === '/{_locale}/documentation/') {
            delete data.spec.paths[kPath];
            continue;
        }
        
        var path = data.spec.paths[kPath];
        var kMethods = Object.keys(path);
        for(var iMethod = 0; iMethod < kMethods.length; iMethod++) {
            var kMethod = kMethods[iMethod];
            var method = path[kMethod];
            if(kMethod === 'post' && method.tags && method.tags.indexOf('AuthToken') !== -1) {
                continue;
            }
            
            if(method.tags) {
                tags = tags.concat(method.tags);
            }
            
            if(!method.security) {
                method.security = [];
            }
            method.security.push({
                api_key: []
            });
        }
    }
    
    tags = tags.filter(function (value, index, self) { 
        return self.indexOf(value) === index;
    });
    tags.sort();    
    data.spec.tags = tags.map(function(value) {
        return { name: value };
    });

    const ui = SwaggerUIBundle({
        spec: data.spec,
        dom_id: '#swagger-ui',
        validatorUrl: null,
        presets: [
            SwaggerUIBundle.presets.apis,
            SwaggerUIStandalonePreset
        ],
        plugins: [
            SwaggerUIBundle.plugins.DownloadUrl
        ],
        layout: 'StandaloneLayout'
    });

    window.ui = ui;
};
