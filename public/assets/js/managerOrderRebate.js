function rebate(rebate_percent) {
    let contract_price = 0, 
        installation_price = 0, 
        courier_price = 0;

    let match_contract_price = document.cookie.match(new RegExp('(^| )' + "contract_price" + '=([^;]+)'));
    let match_installation_price = document.cookie.match(new RegExp('(^| )' + "installation_price" + '=([^;]+)'));
    let match_courier_price = document.cookie.match(new RegExp('(^| )' + "courier_price" + '=([^;]+)'));

    if (match_contract_price)
      contract_price = parseInt(match_contract_price[2]);

    if (match_installation_price)
      installation_price = parseInt(match_installation_price[2]);

    if (match_courier_price)
      courier_price = parseInt(match_courier_price[2]);

    let after_rebate = (contract_price - installation_price - courier_price) * (100 - rebate_percent) / 100 + installation_price + courier_price;
    let rebate = contract_price - after_rebate;

    rebate = rebate.toLocaleString();
    after_rebate = after_rebate.toLocaleString();
    
    $('div.rebate span').text(rebate);
    $('div.after_rebate span').text(after_rebate);
}