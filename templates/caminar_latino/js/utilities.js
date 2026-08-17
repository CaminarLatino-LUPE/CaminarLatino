/* ------ Establish Namespace ------- */

let utilities = utilities || {
  /**
  * window width
  * @type {int}
  */
  viewport: window.innerWidth,
  /**
  * window height
  * @type {int}
  */  
  viewHeight: window.innerHeight,

  /**
  * Check if jQuery is loaded
  */
  jQuery: () => {
    if (typeof jQuery == 'undefined') {
      return false
    }
    else {
      return true;
    }    
  },

  /**
  * Function to limit function call
  * @param  {Function} fn    [the function to use]
  * @param  {float}   delay [the time to delay]
  * @return {void}         [description]
  */
  debounce: (fn, delay) => {
    let timer = null;
    return function() {
      let context = this;
      let args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function() {
        fn.apply(context, args);
      }, delay);
    }
  },


  /**
   * Function to see if class exists in an element
   * @param  {DOM Object}  element   [the element to test]
   * @param  {String}  className [The classname to query for]
   * @return {Boolean}           [description]
   */
  hasClass: (element, className) => {
      return element.className && new RegExp("(^|\\s)" + className + "(\\s|$)").test(element.className);    
  },  


  /**
   * Function to toggle class
   * @param {string} className    The name of the class to toggle
   * @param {[type]} element The dom element
   */
  toggleClass: (element, className) => {
    let classesString;
    classesString = element.className || "";
    if (classesString.indexOf(className) === -1) {
      element.className += " " + className;
    }
    else {
      element.className = element.className.replace(className, '');
    }
  },

  /**
   * Function to add class
   * @param {DOM element} element   [the element to add the class to]
   * @param {string} className [the classname added]
   */
  addClass: (element, className) => {
    let classList = className.split(' ');
    if (element.classList) element.classList.add(classList[0]);
    else if (!utilities.hasClass(element, classList[0])) element.className += " " + classList[0];
    if (classList.length > 1) utilities.addClass(element, classList.slice(1).join(' '));
  },

  /**
   * Function to remove class
   * @param  {DOM element} element        [the element to remove the class from]
   * @param  {string} className [the class to be removed]
   * @return {null}           [description]
   */
  removeClass: (element, className) => {
    let classList = className.split(' ');
    if (element.classList) {
      element.classList.remove(classList[0]);  
    }
    else if(utilities.hasClass(element, classList[0])) {
    let reg = new RegExp('(\\s|^)' + classList[0] + '(\\s|$)');
    element.className=element.className.replace(reg, ' ');
    }
    if (classList.length > 1) {
      utilities.removeClass(element, classList.slice(1).join(' '));
    }
  },

  /**
   * Function to check if item exists in an array
   * @param  {array} array [the array to search]
   * @param  {mix} item  [the item to search for]
   * @return {bool}       []
   */
  inArray: (item, array) => {
      for (let i = 0; i < array.length; i++) {
          if (array[i] === item) {
              return true;
          }
      }
      return false;
  },

  /**
   * Function to serialize form data for AJAX
   * @param  {HTML form element} form [The form to be used]
   * @return {null}      
   */
  serialize: (form) => {
  const serialized = [];

  [].forEach.call(form.elements, function(element){
    // Don't serialize if it has no name or is disabled
    if (!element.name || element.disabled) return;

    // FIX: Read the structural attribute directly from the HTML to avoid naming conflicts
    const fieldType = element.getAttribute('type') || element.type;

    // Skip buttons and files
    if (['file', 'reset', 'submit', 'button'].includes(fieldType)) return;

    // If a multi-select, get all selections
    if (fieldType === 'select-multiple') {
      [].forEach.call(element.options, function(option){
        if (option.selected) {
          serialized.push(encodeURIComponent(element.name) + "=" + encodeURIComponent(option.value));
        }
      });
    }
    // FIX: Use our safe fieldType variable here to capture the checkbox correctly!
    else if ((fieldType !== 'checkbox' && fieldType !== 'radio') || element.checked) {
      serialized.push(encodeURIComponent(element.name) + "=" + encodeURIComponent(element.value));
    }

  });

  return serialized.join('&');
  },
  
  /**
   * Promised based AJAX wrapper function
   * @param  {string} url    [location to send request]
   * @param  {string} method [request type]
   * @return {promise}        jalene
   */
  ajaxRequest: (url, data, method) => {
      // Create the XHR request
      let request = new XMLHttpRequest();

      // Return a Promise
      return new Promise(function(resolve, reject){

          request.onreadystatechange = () => {
              // Only run if the request is complete
              if (request.readyState !== 4) {
                  return;
              }
              // Process the response
              if (request.status >= 200 && request.status < 300) {
                  // Successful
                  resolve(request);
              } else {
                  // Failed
                  reject({
                      status: request.status,
                      statusText: request.statusText
                  });
              }           
          }

          // Setup our HTTP request       
          request.open(method || 'GET', url, true);
          request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

          // Send the request
          request.send(data);     

      });
  },

  /**
   * Function to smooth scroll to specified section
   * @param  {timestamp} startTime         [starting time of event]
   * @param  {timestamp} currentTime       [current time]
   * @param  {int} duration          [duration of animation]
   * @param  {DOM} scrollEndElemTop  [top of the element to scroll to]
   * @param  {DOM} startScrollOffset [the starting scroll position]
   * @return {null}                   [description]
   */
  scrollToElem: (startTime, currentTime, duration, scrollEndElemTop, startScrollOffset) => {
    const easeInCubic = function (t) { return t*t*t }

     const runtime = currentTime - startTime;
     let progress = runtime / duration;
     
     progress = Math.min(progress, 1);
     
     const ease = easeInCubic(progress);
     
     window.scroll(0, startScrollOffset + (scrollEndElemTop * ease));if(runtime < duration){
       requestAnimationFrame((timestamp) => {
         const currentTime = timestamp || new Date().getTime();
         utilities.scrollToElem(startTime, currentTime, duration, scrollEndElemTop, startScrollOffset);
       })
     }
   },

  /**
   * Function to filter array
   * @param  {array} array             [the array to filter]
   * @param  {function} conditionFunction [the function used to filter]
   * @return {array}                   [description]
   */
  filterArray: (array, conditionFunction) => {
    const validValues = [];
      for (let index = 0; index < array.length; index++) {
          if (conditionFunction(array[index])) {
              validValues.push(array[index]);
          }
      }
      return validValues;
  },


  /**
   * Function to return the closest parent of specified selector
   * @param  {HTML object} elem     [the child object to start the search up from]
   * @param  {selector} selector [the selector type to search for]
   * @return {HTML object}          [the item found]
   */
  getClosest: (elem, selector) => {

    // Element.matches() polyfill
    if (!Element.prototype.matches) {
        Element.prototype.matches =
            Element.prototype.matchesSelector ||
            Element.prototype.mozMatchesSelector ||
            Element.prototype.msMatchesSelector ||
            Element.prototype.oMatchesSelector ||
            Element.prototype.webkitMatchesSelector ||
            function(s) {
                var matches = (this.document || this.ownerDocument).querySelectorAll(s),
                    i = matches.length;
                while (--i >= 0 && matches.item(i) !== this) {}
                return i > -1;
            };
    }

    // Get the closest matching element
    for ( ; elem && elem !== document; elem = elem.parentNode ) {
      if ( elem.matches( selector ) ) return elem;
    }
    return null;   

  },


  /**
   * Function to check if element is in the viewport
   * @return {[type]} [description]
   */
  inView: (element) => {
    const rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
  },


  updateViewSize: () => {
    utilities.viewport = window.innerWidth;
    utilities.viewHeight = window.innerHeight;
  },

  // Function to be called when DOM is ready
  callback: () => {
    window.addEventListener('resize', utilities.debounce(utilities.updateViewSize, 100));
  }

};









// Check if DOM is ready
if ( document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll)) {
  utilities.callback();
} else {
  document.addEventListener('DOMContentLoaded', utilities.callback);
}
