let buttonAdd = document.querySelectorAll(".btn-addCart");
let cartShow = document.getElementById('syliusCartIcon');

const beginLoading = (elem) => {
    elem.classList.add('spinner');
    elem.classList.remove('cart');
    elem.classList.add('load');
}

const finishedLoading = (elem) => {
    elem.classList.remove('load');
    elem.classList.remove('spinner');
    elem.classList.add('cart');
}

const handleAddCart = (e) => {

    beginLoading(cartShow);

    const productId = e.target.getAttribute('productId');
    const token = e.target.getAttribute('token')

    fetch('/add-item?productId='+productId,{
        method: "POST",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            "sylius_add_to_cart[cartItem][quantity]": 1,
            "sylius_add_to_cart[_token]": token
        })
    })
    .then(res =>{ 
        if(res.status == 200)
        {
            fetch("/partial/cart-list",{
                headers: {
                    'Accept': 'text/html',
                }
            }).then(reponse => reponse.text())
            .then(reponse => loadNewCart(reponse,res.json()));
        }
        else
        {
            finishedLoading(cartShow);
        }   
    })

};

const loadNewCart = (newHTML,json) => {
    
    json.then(res => {
        finishedLoading(cartShow);

        document.getElementById('sylius-cart-widget-list').innerHTML = newHTML;
        document.getElementById('sylius-cart-total').innerHTML = Number.parseFloat(res['total']/100).toFixed(2)+"€";
    });
}

buttonAdd.forEach(elem => elem.addEventListener("click",handleAddCart));