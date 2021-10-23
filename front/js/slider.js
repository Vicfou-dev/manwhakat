class Slider {
    constructor(element1, element2) {
        this.manager;
        this.class = "";

        if(Array.isArray(element1) == false) {
            element1 = [element1];
        }

        if(Array.isArray(element2) == false) {
            element2 = [element2];
        }

        this.element1 = element1;
        this.element2 = element2;
        this.startStep = [];
        this.previousStep = [];

        this.element1.forEach( element => {
            this.startStep.push(document.getElementById(element))
        })

        this.element2.forEach( element => {
            var el = document.getElementById(element);
            console.log(element);
            this.previousStep.push(el)
        })
        console.log(this.previousStep);
        this.init();
    }

    init() {
        this.startStep.forEach(element => {
            element.addEventListener('click', () => this.move());
        })
        
        this.previousStep.forEach(element => {
            element.addEventListener('click', () => this.move(true));
        })
        
    }

    move(start = false)
    {
        var container = start ? this.startStep[0].parentElement : this.previousStep[0].parentElement;

        container.scrollIntoView();
    }
}
export default Slider;
