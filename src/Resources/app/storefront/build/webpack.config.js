const { join, resolve } = require('path');

module.exports = () => {
    return {
        resolve: {
            alias: {
                '@voltimax': resolve(
                    join(__dirname, '..', 'src')
                )
            }
        }
    };
};