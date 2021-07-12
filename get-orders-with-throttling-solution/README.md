# Exemplo de polling para o endpoint GET ORDERS da SP-API

Cada arquivo tem uma função específica:
- `config.php` é onde as credenciais de autenticação ficam.
- `get-orders.php` é o arquivo responsável pela chamada para o endpoint GET ORDERS, trazendo todas as orders de um *Seller*.
- `get-order-info.php` é o arquivo responsável por trazer dados específicos de uma order, incluindo dados sensíveis que precisam do **RDT**.
- `index.php` contém a tabela HTML para exibição das orders, o polling com `setInterval` e um botão para trazer dados específicos de uma order.

A biblioteca usada - [jlevers/selling-partner-api](https://github.com/jlevers/selling-partner-api) - já faz todas as tratativas do `Restricted Data Token (RDT)`.
