# Exemplos de tratativas para o Throttling

Execute os códigos utilizando a extensão [Code Runner](https://marketplace.visualstudio.com/items?itemName=formulahendry.code-runner) para VSCode

Os arquivos contém funções que podem ajudar a tratar o Throttling, como por exemplo, funções que permitem calcular quanto tempo demora para que você possa fazer uma requisição para um endpoint ou quanto tempo demora para recuperar todas as requisições por completo. No arquivo `dealing-with-throttling.js` há um exemplo onde você pode fazer requisições tratando o throttling.

Para calcular o throttling as funções precisam receber dois parâmetros: `rate` ( e `burst`, estes valores podem ser encontrados na documentação da [Selling Partner API](https://amazon-br-dx.readme.io/).
