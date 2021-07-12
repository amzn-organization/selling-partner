<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notification</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  <style>
    .color-green {
      color: #32ab52 !important;
    }

    .dialog-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100vh;
      background-color: rgba(0, 0, 0, .35);
    }

    .dialog {
      position: fixed;
      margin: auto;
      top: 0;
      bottom: 0;
      right: 0;
      left: 0;
      width: 720px;
      height: 520px;
      background-color: #f2f2f2;
      z-index: 2;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 1rem 2.5rem;
      box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
    }

    .dialog-title {
      text-align: center;
    }

    .dialog-body {
      max-height: 405;
      overflow: auto;
    }

    .dialog-footer {
      display: flex;
      flex-wrap: wrap;
      justify-content: flex-end;
    }
  </style>
</head>

<body>
  <h1 class="text-center mt-5">Websocket Orders Example</h1>
  <table class="table table-striped">
    <thead class="">
      <tr>
        <th>Index</th>
        <th>OrderStatus</th>
        <th>AmazonOrderId</th>
        <th>PurchaseDate</th>
        <th>OrderTotal</th>
        <th>EarliestShipDate</th>
        <th>LatestShipDate</th>
        <th>Detalhes</th>
      </tr>
    </thead>

    <tbody id="table-body">
    </tbody>
  </table>

  <div class="dialog" style="display: none">
    <h2 class="dialog-title">Informações do Pedido</h2>

    <div class="dialog-body">
      <div class="text-center">
        <img src="./spinner.svg" alt="Carregando...">
      </div>
    </div>

    <div class="dialog-footer">
      <button class="btn btn-danger" onclick="closeDetails()">Fechar</button>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/date-fns/1.30.1/date_fns.min.js" integrity="sha512-F+u8eWHrfY8Xw9BLzZ8rG/0wIvs0y+JyRJrXjp3VjtFPylAEEGwKbua5Ip/oiVhaTDaDs4eU2Xtsxjs/9ag2bQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script>
    let dialogLoadingContent = null;
    let orders = [];

    document.addEventListener('DOMContentLoaded', async () => {
      dialogLoadingContent = document.querySelector('.dialog-body').innerHTML;

      if (!document.querySelector('.dialog-background')) {
        let dialogBackground = document.createElement('div');
        dialogBackground.classList.add('dialog-background');
        dialogBackground.style.display = 'none';
        dialogBackground.addEventListener('click', closeDetails);
        document.querySelector('body').appendChild(dialogBackground);
      }

      const json = await getOrders();
      json.Orders.reverse();
      updateTable(json.Orders);

      setInterval(async (e) => {
        const json = await getOrders();
        updateTable(json.Orders.reverse());
      }, 8500);
    });

    async function getOrders() {
      const response = await fetch('./get-orders.php')
      const json = await response.json();
      orders = json;
      return json;
    }

    async function getOrderInfo(orderId) {
      const response = await fetch(`./get-order-info.php?order_id=${orderId}`)
      const json = await response.json();
      return json;
    }

    function updateTable(orders) {
      const tbody = document.querySelector('#table-body');
      tbody.innerHTML = '';

      let count = -1;
      for (const order of orders) {
        count++;
        tbody.innerHTML += `
          <tr order-index="${count}">
            <td>${count + 1}</td>
            <td>${order.OrderStatus}</td>
            <td>${order.AmazonOrderId}</td>
            <td>${formatDate(order.PurchaseDate)}</td>
            <td>${order.OrderTotal ? formatMoney(order.OrderTotal.Amount).concat(' ' + order.OrderTotal.CurrencyCode) : '0.00 BRL'}</td>
            <td>${formatDate(order.EarliestShipDate)}</td>
            <td>${formatDate(order.LatestShipDate)}</td>

            <td>
              <button class="btn btn-primary" onclick="getDetails('${order.AmazonOrderId}')">
                Visualizar
              </button>
            </td>
          </tr>
          `;

        const isUpdated = wasRecentlyUpdated(order.LastUpdateDate);

        if (isUpdated) {
          const row = document.querySelector(`[order-index="${count}"]`);
          row.classList.add('color-green');
        }
      }
    }

    function formatMoney(amount) {
      return amount.toLocaleString('pt-br', {
        style: 'currency',
        currency: 'BRL'
      });
    }

    function formatDate(date) {
      date = new Date(date);
      return date ? date.toLocaleDateString("pt-BR") : date;
    }

    function wasRecentlyUpdated(date, withinMinutes = 60) {
      let lastUpdatedDate = new Date(date);
      const minutes = Math.abs(dateFns.differenceInMinutes(lastUpdatedDate, new Date()));

      if (Math.floor(minutes) < 60) {
        return true;
      }

      return false;
    }

    async function getDetails(orderId) {
      document.querySelector('.dialog-background').style.display = '';
      document.querySelector('.dialog').style.display = '';

      const orderInfo = await getOrderInfo(orderId);
      const order = orders.Orders.find(o => o.AmazonOrderId === orderInfo.address.AmazonOrderId);

      document.querySelector('.dialog-body').innerHTML = `<pre>${JSON.stringify(orderInfo)}</pre>`;

      let buyerInfos = '';
      let items = '';

      if (orderInfo.buyerInfo.BuyerTaxInfo && orderInfo.buyerInfo.BuyerTaxInfo.TaxClassifications) {
        for (const buyerInfo of orderInfo.buyerInfo.BuyerTaxInfo.TaxClassifications) {
          buyerInfos += `${buyerInfo.Name}: ${buyerInfo.Value}<br>`
        }
      }

      if (orderInfo.items.OrderItems) {
        for (const item of orderInfo.items.OrderItems) {
          items += `
            OrderItemID: ${item.OrderItemId} <br>
            SKU: ${item.SellerSKU} <br>
            Nome do Produto: ${item.Title} <br>
            Quantidade: ${item.QuantityOrdered} <br>
            Preço: ${item.ItemPrice.Amount} ${item.ItemPrice.CurrencyCode} <br>
            Taxa: ${item.ItemTax.Amount} ${item.ItemTax.CurrencyCode} <br>
            <hr>
          `;
        }
      }

      items = items.replace(/<hr>\s*$/, '')

      document.querySelector('.dialog-body').innerHTML = `
        <h4>Endereço:</h4>
        Cidade: ${orderInfo.address.ShippingAddress.City} <br>
        Código do País: ${orderInfo.address.ShippingAddress.CountryCode} <br>
        Nome: ${orderInfo.address.ShippingAddress.Name} <br>
        Código Postal: ${orderInfo.address.ShippingAddress.PostalCode} <br>
        Estado: ${orderInfo.address.ShippingAddress.StateOrRegion} <br>
        
        <hr>

        <h4>Informações do comprador:</h4>
        Município: ${orderInfo.buyerInfo.BuyerCounty} <br>
        Email: ${orderInfo.buyerInfo.BuyerEmail} <br>
        ${buyerInfos}

        <hr>

        <h4>Itens:</h4>
        ${items}

        <hr>

        <h4>Outros:</h4>
        Método de pagamento: ${JSON.stringify(order.PaymentMethodDetails)}
      `;
    }

    function closeDetails() {
      document.querySelector('.dialog').style.display = 'none';
      document.querySelector('.dialog-background').style.display = 'none';

      document.querySelector('.dialog-body').innerHTML = dialogLoadingContent;
    }
  </script>
</body>

</html>