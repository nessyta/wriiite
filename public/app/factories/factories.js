app.factory('BooksFactory', function ($resource) {

    var factory = {};
    
    factory.getBooks = function () {

        return $resource('http://localhost:port/wriiite/public/api/v1/books/:id', {
            id: '@id'
        }, {
            get: {
                method: 'GET',
                params: {
                    id: "@id",
                    port : ':8888'
                }
            }
        })

    };

    factory.getAuthors = function () {
        return $resource('http://localhost:port/wriiite/public/api/v1/books/:id/users', {
            id : '@id'
        }, {
            get : {
                method : 'GET',
                params : {
                    id      : '@id',
                    port    : ':8888'
                }
            }
        });
    };

    return factory;

   
});


app.factory('UsersFactory', function ($resource) {

    factory = {};

    factory.getUsers = function () {
        return $resource('http://localhost:port/wriiite/public/api/v1/users/:id', {
            id: '@id'
        }, {
            get: {
                method: 'GET',
                params: {
                    id: "@id",
                    port : ':8888'
                },
            }
        })
        
    };

    factory.getPagesByAuthor = function () {
        return $resource('http://localhost:port/wriiite/public/api/v1/users/:id/pages', {
            id : '@id'
        }, {
            get : {
                method : 'GET',
                params : {
                    id      : '@id',
                    port    : ':8888'
                }
            }
        });
    };

    factory.getBooksByAuthor = function () {
        return $resource('http://localhost:port/wriiite/public/api/v1/users/:id/books', {
            id : '@id'
        }, {
            get : {
                method : 'GET',
                params : {
                    id      : '@id',
                    port    : ':8888'
                }
            }
        });
    };
    
    return factory;
});


app.factory('PagesFactory', function ($resource) {

    factory = {};

    factory.getPages = function () {
        return $resource('http://localhost:port/wriiite/public/api/v1/pages/:id', {
            id: '@id'
        }, {
            get: {
                method: 'GET',
                params: {
                    id: "@id",
                    port : ':8888'
                },
            }
        })
        
    };
    
    return factory;
});