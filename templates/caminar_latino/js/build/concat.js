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

/* ------ Establish Namespace ------- */

const caminarlatino = caminarlatino || {


  /**
   * Function to reveal main nav in movile viewport
   * @return {null} [description]
   */
  mainNavReveal: () => {
      // Mobile Main Nav Reveal
      const navToggle = document.querySelector('.navbar-toggle');
      const mainNav = document.querySelector('.main-nav');
      let body = document.querySelector('body');

      if(navToggle !== null && mainNav !== null) {
          
          navToggle.addEventListener("click",function(e){
              let display = window.getComputedStyle(mainNav, null).getPropertyValue('display');          
              if(display != 'block') {
                if (window.jQuery) {
                  jQuery(mainNav).velocity({top: "72px"}, {display: "block"}, {duration: 500, easing: [0.61,0.09,0.59,0.71]}); 
                }
                else {
                  Velocity(mainNav,  {top: "72px"}, {display: "block"}, {duration: 500, easing: [0.61,0.09,0.59,0.71]}); 
                }                          
                  this.setAttribute('aria-expanded', 'true'); 
                  mainNav.setAttribute('aria-hidden', 'false');  
                  utilities.addClass(navToggle, 'open');                                    
              }
              else {
                if (window.jQuery) {
                  jQuery(mainNav).velocity({top: "-465px"}, {display: "none"}, {duration: 500, easing: [0.61,0.09,0.59,0.71]});
                }
                else {
                  Velocity(mainNav,  {top: "-465px"}, {display: "none"}, {duration: 500, easing: [0.61,0.09,0.59,0.71]});
                }                          
                  this.setAttribute('aria-expanded', 'false'); 
                  mainNav.setAttribute('aria-hidden', 'true');  
                  utilities.removeClass(navToggle,'open');                                  
              }

          }); 
     
      }
      
  },


  /**
   * Function to reveal submenus
   * @return {[type]} [description]
   */
  subMenuReveal: () => {

    const mainParents = document.querySelectorAll('.main-nav .parent');
    if(mainParents !== null) {

      // Reveal Animation
      const hoverInstance = new SV.HoverIntent(mainParents, {
        onEnter: function(targetItem) {
          utilities.addClass(targetItem, 'open');
        },
        onExit: function(targetItem) {
          utilities.removeClass(targetItem, 'open');
        },
      });

      // Set Attributes
      mainParents.forEach((e,i) => {
        e.addEventListener('mouseover', () => {
          e.setAttribute('aria-expanded', 'true');
          let subMenuPanel = e.querySelector('ul.mod-menu__sub');
          subMenuPanel.setAttribute('aria-hidden', 'false');
          subMenuPanel.setAttribute('aria-expanded', 'true');
        });


        e.addEventListener('mouseleave', () => {
          e.setAttribute('aria-expanded', 'false');
          let subMenuPanel = e.querySelector('ul.mod-menu__sub');
          subMenuPanel.setAttribute('aria-hidden', 'true');
          subMenuPanel.setAttribute('aria-expanded', 'false');
        });



      })
    }


      // Trigger on keyboard enter
      document.addEventListener('keyup', function(e){
          if(utilities.hasClass(e.target, 'nav-header')) {
              if(e.keyCode === 13) {
                const tabGrandParent = e.target.parentNode.parentNode;
                let tabChilds = tabGrandParent.querySelectorAll('.parent');
                tabChilds.forEach((e,i) => {
                  let tabChildLinks = tabGrandParent.querySelectorAll('.parent .nav-item a');
                  tabChildLinks.forEach((e,i) => {
                    e.setAttribute('tabindex', '-1');
                  });                        
                  utilities.removeClass(e, 'open');
                });
                let tabParent = e.target.parentNode;
                let tabLinks = tabParent.querySelectorAll('.nav-item a');
                tabLinks.forEach((e,i) => {
                  e.setAttribute('tabindex', '0');
                });                
                utilities.addClass(tabParent, 'open');

              }
              
          }
      });



  },


  /**
   * Function to trigger on scroll functions
   * @return {null} [description]
   */
  detectScroll: () => {
    // The top of the page
    const scrollTop = window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop;

    // Reveal or hide the button
    const scrollUpBtn = document.querySelector('.btn-scroll-up');
    if(scrollUpBtn != null) {
      if(scrollTop > 1600) {
        utilities.addClass(scrollUpBtn, 'active');
      }
      else {
        utilities.removeClass(scrollUpBtn, 'active');
      }        
    }


    const ourLogo = document.querySelector('.our-logo-img-outer');
    if(ourLogo != null && utilities.inView(ourLogo)) {
      utilities.addClass(ourLogo, 'visible');
    }


  },

  /**
   * Function to trigger smoolth scroll for main nav links
   * @return {[type]} [description]
   */
  smoothNav: function() {

    // const anchorLinks = document.querySelectorAll('a.smooth-scroll');
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    if(anchorLinks != null) {
      [].forEach.call(anchorLinks, function(link){
          link.addEventListener('click', function(e){
            let anchorHref = link.getAttribute('href').replace('/', '');
            if(anchorHref.length > 1) {
              e.preventDefault();        
              let anchor = document.querySelector(anchorHref);
              if(anchor != null) {                 
                  if (window.jQuery) {
                    jQuery(anchor).velocity('scroll' , {duration: 600, easing: [0.240, 0.520, 0.810, 0.760]}); 
                  }
                  else {
                    Velocity(anchor, 'scroll' , {duration: 600, easing: [0.240, 0.520, 0.810, 0.760]}); 
                  }                
              }
            }
          });
      });
    }    
  },

  /**
   * Function to display alert
   * @param  {DOM element} parent  [the item to serve as outer wrapper for the alert]
   * @param  {array} messages [the message(s) to display]
   * @param  {string} type    [the type of alert to display (sucess, warning, error, message)]
   * @param  {boolean} close   [whether to make alert closeable]
   * @return {void}         [description]
   */
  showAlert: (parent, messages, type, close) => {
    let title = type.charAt(0).toUpperCase() + type.slice(1);
      if(messages.length > 0){      
        var alertCode = '<div class="alert alert-' + type +  (close ? ' alert-dismissable"><button type="button" class="close" data-dismiss="alert"></button>' : '">');
        alertCode += '<h4>' + title + '</h4>';
        // for list of messages
        if((messages instanceof Array) || (Object.prototype.toString.apply(messages) === '[object Array]')) {
          alertCode += '<ul>';
            for(var i=0; i<messages.length; i++){
              alertCode += '<li>' + messages[i] + '</li>';
            }
          alertCode += '</ul>';
        }
        else {
          alertCode += '<div>'+ messages + '</div>';
        }


        alertCode += '</div>';
        if(parent != null) {
          parent.innerHTML = alertCode;
          if(close) {
            utilities.addClass(parent, 'overlay');
          }        
        }
        if(!close) {
          if (window.jQuery) {
            jQuery(parent).velocity('scroll' , {duration: 500, easing: [0.61,0.09,0.59,0.71]}); 
          }
          else {
            Velocity(parent, 'scroll' , {duration: 500, easing: [0.61,0.09,0.59,0.71]}); 
          }             
        }
      }

  },

  /**
   * Self-executing function to add click event to alert close buttons
   * 
   * @return {void}      [description]
   */
  closeAlert: () => {
    document.body.addEventListener('click', function(e){
      if(e.target.getAttribute('data-dismiss') != null) {
        let alert = document.querySelector('.' + e.target.getAttribute('data-dismiss'));
        if(utilities.hasClass(alert.parentNode, 'overlay')) {
          utilities.removeClass(alert.parentNode, 'overlay');
        }
        alert.parentNode.removeChild(alert);
      }    
    })  
  },


  /**
   * Function to display alert
   * @param  {DOM element} parent  [the item to serve as outer wrapper for the alert]
   * @param  {array} messages [the message(s) to display]
   * @param  {string} type    [the type of alert to display (sucess, warning, error, message)]
   * @param  {boolean} close   [whether to make alert closeable]
   * @return {void}         [description]
   */
  showModal: () => {
    // Trigger on click
    document.getElementsByTagName('body')[0].addEventListener('click', function(e){
      

      if((utilities.hasClass(e.target, 'btn-modal') )) {
        e.preventDefault();

        let modalTarget = e.target.getAttribute('data-target');
        let modal = document.querySelector('[data-modal="' + modalTarget + '"]');
 
        if(modal !== null) {

          // Animate modal
          if (window.jQuery) {
            jQuery(modal).velocity({opacity: 1, translateX: ['-50%', '-50%'], translateY: ['-50%']},  {display: 'block'}, {duration: 500, easing: [0.61,0.09,0.59,0.71]}); 
          }
          else {
            Velocity(modal, {opacity: 1, translateX: ['-50%', '-50%'], translateY: ['-50%']},  {display: 'block'}, {duration: 500, easing: [0.61,0.09,0.59,0.71]});   
          }
          

          // Add Overlay
          utilities.addClass(body, 'overlay');

        }
        e.target.blur();

      };
    });

      // Trigger on keyboard enter
      if(document.querySelector('.page-levers') !== null) {
        document.addEventListener('keyup', function(e){
          let leverBtn = e.target.getAttribute('class') != null ? e.target.parentNode.getAttribute('class').match('knob-circle') : null;
            if((utilities.hasClass(e.target, 'btn-modal') || leverBtn != null) && modal != null) {

                if(e.keyCode === 13) {
                
                  // Animate modal (only from Levers Page)
                  if(leverBtn != null) {
                    if (window.jQuery) {
                      jQuery(modal).velocity({opacity: 1, translateX: ['-50%', '-50%'], translateY: ['-50%']},  {display: 'block'}, {duration: 500, easing: [0.61,0.09,0.59,0.71]}); 
                    }
                    else {
                      Velocity(modal, {opacity: 1, translateX: ['-50%', '-50%'], translateY: ['-50%']},  {display: 'block'}, {duration: 500, easing: [0.61,0.09,0.59,0.71]}); 
                    }                    

                    // Add Overlay
                    utilities.addClass(body, 'overlay');
                  }

                  // Lock focus inside modal
                  const focusableElements = 'button, [href], input:not([type="hidden"]), select, textarea, [tabindex]:not([tabindex="-1"])';
                  const firstFocusableElement = modal.querySelectorAll(focusableElements)[0];
                  const focusableContent = modal.querySelectorAll(focusableElements);
                  const lastFocusableElement = focusableContent[focusableContent.length - 1];
               
                  document.addEventListener('keydown', function(e) {
                    let isTabPressed = e.key === 'Tab' || e.keyCode === 9;

                    if (!isTabPressed) {
                      return;
                    }

                    if (e.shiftKey) { // if shift key pressed for shift + tab combination
                      if (document.activeElement === firstFocusableElement) {
                        lastFocusableElement.focus(); // add focus for the last focusable element
                        e.preventDefault();
                      }
                    } else { // if tab key is pressed
                      if (document.activeElement === lastFocusableElement) { // if focused has reached to last focusable element then focus first focusable element after pressing tab
                        firstFocusableElement.focus(); // add focus for the first focusable element
                        e.preventDefault();
                      }
                    }
                  });

                  firstFocusableElement.focus();


                }
                
            }
        });
      }

  },






  /**
   * Self-executing function to add click event to modal close buttons
   * 
   * @return {void}      [description]
   */
  closeModal: () => {    
    let body = document.getElementsByTagName('body')[0];
    document.body.addEventListener('click', function(e){
      if(utilities.hasClass(e.target, 'close-modal')) {
        
        let modal = e.target.parentNode;
          if (window.jQuery) {
            jQuery(modal).velocity({opacity: 0, translateX: ['-50%', '-50%'], translateY: ['-30%']},  {display: 'none'}, {duration: 500, easing: [0.61,0.09,0.59,0.71]}); 
          }
          else {
            Velocity(modal, {opacity: 0, translateX: ['-50%', '-50%'], translateY: ['-30%']},  {display: 'none'}, {duration: 500, easing: [0.61,0.09,0.59,0.71]}); 
          }        

        // Remove Overlay
        if(utilities.hasClass(body, 'overlay')) {
          utilities.removeClass(body, 'overlay');
        }

        modal.setAttribute('tabindex', '-1');


      }    
    })  
  },


  /**
   * Function to clear checkboxes and search box and submit form
   * @return {null} [description]
   */
  clearForm: () => {
    const clrBtns = document.querySelectorAll('.search-clear');
    if(clrBtns != null) {
      [].forEach.call(clrBtns, function(btn){
        btn.addEventListener('click', function(e){
          e.preventDefault();
          const parentForm = utilities.getClosest(btn, 'form');
          let searchText = parentForm.querySelectorAll('input[type="text"]');
          if(searchText != null) {
            [].forEach.call(searchText, function(input){
              input.value = '';
            });
          }        
          let checkboxes = parentForm.querySelectorAll('input[type="checkbox"]'); 
          if(checkboxes != null) {
            [].forEach.call(checkboxes, function(box){
              box.checked = false;
            });     
          }       
          let selectLists = parentForm.querySelectorAll('select');
          if(selectLists != null) {
            [].forEach.call(selectLists, function(list){
              list.selectedIndex = 0;
            });     
          }                    
          // if(document.getElementById('adminForm')) {
          //   document.getElementById('adminForm').submit();
          // }            
          if(parentForm != null) {
            parentForm.submit();
          }  

        }) 
      })
    }
  },



  /**
   * Function to initialize tabs
   * @return {null} [description]
   */
  tabs: () => {

      //addEventListener on mouse click
      
      document.addEventListener('click', function (e) {          
          //check is the right element clicked
          if (!e.target.matches('.tabs .nav-tabs a')) return;
          else{
            e.preventDefault();
            const tabButton = e.target;
            if(!utilities.hasClass(tabButton, 'active')){

              const tabsContainer = utilities.getClosest(tabButton, '.tabs');

              const tabBtns = tabsContainer.querySelectorAll('.nav-tabs a');
              [].forEach.call(tabBtns, function(btn){
                utilities.removeClass(btn, 'active');
                btn.setAttribute('aria-selected', 'false');
              });


              const tabPns = tabsContainer.querySelectorAll('.tab-pane');
              [].forEach.call(tabPns, function(pane){
                // Close pane
                let display = window.getComputedStyle(pane, null).getPropertyValue('display');
                if(display == 'block') {
                  pane.setAttribute('aria-hidden', 'true');
                  if (window.jQuery) {
                    jQuery(pane).velocity('slideUp' , {duration: 500, easing: [0.61,0.09,0.59,0.71]});
                  }
                  else {
                    Velocity(pane,  'slideUp' , {duration: 500, easing: [0.61,0.09,0.59,0.71]});
                  }                      
                }
                
              });                          
               

              //add active class on clicked tab  
              utilities.addClass(tabButton, 'active');
              tabButton.setAttribute('aria-selected', 'true');
 
              // Open pane          
              const tabPane = document.getElementById(tabButton.getAttribute('aria-controls'));
              tabPane.setAttribute('aria-hidden', 'false');
                if (window.jQuery) {
                  jQuery(tabPane).velocity('slideDown' , {duration: 500, easing: [0.61,0.09,0.59,0.71]}); 
                }
                else {
                  Velocity(tabPane,  'slideDown' , {duration: 500, easing: [0.61,0.09,0.59,0.71]}); 
                }                                                

            }
          }
      });
  },

  /**
   * Function for dropdowns
   * @return {[type]} [description]
   */
  dropDown: () => {
      //addEventListener on mouse click
      
      document.addEventListener('click', function (e) {  
          //check is the right element clicked
          if (!e.target.matches('.dropdown-button')) return;
          else {

            e.preventDefault();
            const dropButton = e.target;
            const dropPane = document.getElementById(dropButton.getAttribute('aria-controls'));
            let display = window.getComputedStyle(dropPane, null).getPropertyValue('display'); 


            if(display != 'block') {
              dropPane.setAttribute('aria-hidden', 'false');
              dropPane.setAttribute('aria-expanded', 'true');
              if (window.jQuery) {
                jQuery(dropPane).velocity('slideDown' , {duration: 500, easing: [0.61,0.09,0.59,0.71]});  
              }
              else {
                Velocity(dropPane,  'slideDown' , {duration: 500, easing: [0.61,0.09,0.59,0.71]});  
              } 
            }
            else {
              dropPane.setAttribute('aria-hidden', 'true');
              if (window.jQuery) {
                jQuery(dropPane).velocity('slideUp' , {duration: 500, easing: [0.61,0.09,0.59,0.71]});  
              }
              else {
                Velocity(dropPane,  'slideUp' , {duration: 500, easing: [0.61,0.09,0.59,0.71]});                  
              }                           
            }
          }        
      });
  },





  /**
   * Function to swap youtube image with video
   * @param {DOM element} element [the thumbnail image that is to be replaced]
   */
  addVideo: (element) => {
      let iframe = document.createElement("iframe");
      let embed = "https://www.youtube.com/embed/ID?enablejsapi=1&autoplay=1&rel=0";
      let parent = element.parentNode;
      iframe.setAttribute("src", embed.replace("ID", parent.dataset.id));
      iframe.setAttribute("frameborder", "0");
      iframe.setAttribute("allowfullscreen", "1");
      parent.parentNode.replaceChild(iframe, parent);

  },


  /**
   * Function to initialize youtube videos
   * @return {null} [description]
   */
  youtubeLoad: () => {
      document.addEventListener('click', function(e){
          if(utilities.hasClass(e.target, 'play') || utilities.hasClass(e.target, 'youtube-thumb')) {
              caminarlatino.addVideo(e.target);
          }
      }); 

      // Trigger on keyboard enter
      document.addEventListener('keyup', function(e){
          if(utilities.hasClass(e.target, 'play')) {
              if(e.keyCode === 13) {
                  caminarlatino.addVideo(e.target);  
              }
              
          }
      });

  }, 

  /**
   * Function to reveal panes
   * @return {[type]} [description]
   */
  paneReveal: () => {
      let paneHeadings = document.querySelectorAll('.pane-heading');
      if(paneHeadings.length){
          [].forEach.call(paneHeadings, function(heading){
              let pane = heading.nextElementSibling;        
              heading.addEventListener('click', function(e){
                  utilities.toggleClass(heading, 'open');
                  let display = window.getComputedStyle(pane, null).getPropertyValue('display');
                  if(display == 'block') {
                    if (window.jQuery) {
                      jQuery(pane).velocity('slideUp', {duration: 500, easing: [0.61,0.09,0.59,0.71]}); 
                    }
                    else {
                      Velocity(pane, 'slideUp', {duration: 500, easing: [0.61,0.09,0.59,0.71]}); 
                    }                      
                      heading.setAttribute('aria-expanded', false);
                      pane.setAttribute('aria-expanded', false);
                      pane.setAttribute('aria-hidden', true);
                      pane.setAttribute('tabindex', '-1');                
                      }
                  else {
                    if (window.jQuery) {
                      jQuery(pane).velocity('slideDown', {duration: 500, easing: [0.61,0.09,0.59,0.71]}); 
                    }
                    else {
                      Velocity(pane, 'slideDown', {duration: 500, easing: [0.61,0.09,0.59,0.71]});                       
                    }                                            
                    heading.setAttribute('aria-expanded', true);
                    pane.setAttribute('aria-expanded', true);
                    pane.setAttribute('aria-hidden', false);
                    pane.setAttribute('tabindex', '0');
                  }          
              });        
          });
      }        
  },




  /**
   * Function to display column on scroll
   * @return {[type]} [description]
   */
  homeSliderFooter: () => {

    const track = document.querySelector(".zlide-track");
    const prevBtn = document.querySelector(".slider-btn-prev");
    const nextBtn = document.querySelector(".slider-btn-next");

    if (track === null || prevBtn === null || nextBtn === null) return;

    let cards = Array.from(track.children);
    let currentIndex = 0;
    let cardWidth = 0;
    let isAnimating = false;
    let originalCount = cards.length;

    const prefersReducedMotion = window.matchMedia(
      "(prefers-reduced-motion: reduce)"
    ).matches;

    // ---- Core measurement & movement ----

    const updateMeasurements = () => {
      const card = cards[0];
      const styles = window.getComputedStyle(track);
      const gap = parseFloat(styles.columnGap || styles.gap) || 0;
      cardWidth = card.getBoundingClientRect().width + gap;
    };

    const moveToIndex = (index, animate = true) => {
      if (animate && !prefersReducedMotion) {
        track.style.transition = "transform 0.45s ease";
      } else {
        // Force the browser to commit "transition: none" before changing
        // the transform, otherwise the instant "jump" can visibly animate.
        track.style.transition = "none";
        void track.offsetWidth; // forced reflow
      }
      track.style.transform = `translateX(-${index * cardWidth}px)`;
    };

    const goTo = (delta) => {
      if (isAnimating) return;
      isAnimating = true;
      currentIndex += delta;
      moveToIndex(currentIndex, true);
      if (prefersReducedMotion) {
        // No transition means no transitionend event will fire.
        handleLoopReset();
        isAnimating = false;
      }
    };

    const nextSlide = () => goTo(-1);
    const prevSlide = () => goTo(1);

    const handleLoopReset = () => {
      if (currentIndex >= originalCount * 2) {
        currentIndex -= originalCount;
        moveToIndex(currentIndex, false);
      } else if (currentIndex < originalCount) {
        currentIndex += originalCount;
        moveToIndex(currentIndex, false);
      }
    };

    const handleTransitionEnd = (e) => {
      if (e.target !== track || e.propertyName !== "transform") return;
      handleLoopReset();
      isAnimating = false;
    };

    // ---- Setup / cloning for infinite loop ----

    const setupInfiniteCarousel = () => {
      const clonesBefore = cards.map((card) => card.cloneNode(true));
      const clonesAfter = cards.map((card) => card.cloneNode(true));
      clonesBefore.forEach((card) => track.prepend(card));
      clonesAfter.forEach((card) => track.append(card));

      cards = Array.from(track.children);
      originalCount = cards.length / 3;
      currentIndex = originalCount;

      updateMeasurements();
      moveToIndex(currentIndex, false);
    };

// ---- Drag / swipe support ----

let isDragging = false;      // true once movement crosses the threshold
let isPointerDown = false;   // true from pointerdown until pointerup/cancel
let dragStartX = 0;
let dragCurrentX = 0;
let dragStartTransform = 0;
const DRAG_THRESHOLD = 8; // px of movement before we treat this as a drag, not a click

const getCurrentTranslateX = () => -(currentIndex * cardWidth);

const onPointerDown = (e) => {
  if (isAnimating) return;
  isPointerDown = true;
  isDragging = false;
  dragStartX = e.clientX;
  dragCurrentX = dragStartX;
  dragStartTransform = getCurrentTranslateX();
  // Note: no setPointerCapture here yet, and no transition change yet —
  // wait until we know this is actually a drag, not a tap/click.
};

const onPointerMove = (e) => {
  if (!isPointerDown) return;
  dragCurrentX = e.clientX;
  const delta = dragCurrentX - dragStartX;

  if (!isDragging && Math.abs(delta) > DRAG_THRESHOLD) {
    isDragging = true;
    track.style.transition = "none";
    track.setPointerCapture(e.pointerId);
  }

  if (isDragging) {
    track.style.transform = `translateX(${dragStartTransform + delta}px)`;
  }
};

const onPointerUp = () => {
  isPointerDown = false;

  if (!isDragging) {
    // Never moved past the threshold — this was a click/tap.
    // Let the browser handle it normally (e.g. follow the link).
    return;
  }

  isDragging = false;

  const delta = dragCurrentX - dragStartX;
  const threshold = cardWidth * 0.2;

  if (delta <= -threshold) {
    isAnimating = true;
    currentIndex += 1;
    moveToIndex(currentIndex, true);
    if (prefersReducedMotion) {
      handleLoopReset();
      isAnimating = false;
    }
  } else if (delta >= threshold) {
    isAnimating = true;
    currentIndex -= 1;
    moveToIndex(currentIndex, true);
    if (prefersReducedMotion) {
      handleLoopReset();
      isAnimating = false;
    }
  } else {
    moveToIndex(currentIndex, true);
  }
};

// After an actual drag, the browser still fires a synthetic "click" on
// whatever was under the pointer at release. Suppress just that one so
// a drag-release over a link doesn't trigger navigation.
const onClickCapture = (e) => {
  if (dragCurrentX !== dragStartX && Math.abs(dragCurrentX - dragStartX) > DRAG_THRESHOLD) {
    e.preventDefault();
    e.stopPropagation();
  }
};

track.addEventListener("pointerdown", onPointerDown);
track.addEventListener("pointermove", onPointerMove);
track.addEventListener("pointerup", onPointerUp);
track.addEventListener("pointercancel", onPointerUp);
track.addEventListener("click", onClickCapture, true); // capture phase, runs before the link's own click

    // ---- Buttons & lifecycle events ----

    nextBtn.addEventListener("click", nextSlide);
    prevBtn.addEventListener("click", prevSlide);
    track.addEventListener("transitionend", handleTransitionEnd);

    // Catch any layout shift affecting the track (resize, font swap,
    // scrollbar appearing, images finishing decode, etc.)
    const ro = new ResizeObserver(() => {
      updateMeasurements();
      moveToIndex(currentIndex, false);
    });
    ro.observe(track);

    setupInfiniteCarousel();


  },


  galleryVideos: () => {
    // Read the video list straight from the <ul> markup above.
    function loadVideosFromDOM() {
      const listItems = document.querySelectorAll('#video-list li');
      if(listItems.length !== 0) {
        document.getElementById('video-list').style.display = 'none';
        return Array.from(listItems).map((li, index) => ({
          id: "v" + index,
          youtubeId: li.dataset.youtubeId,
          title: li.textContent.trim(),
          subtitle: li.dataset.description || ""
        }));
      }

    }



    const videoGalleryPlayer = document.querySelector('.video-gallery-player');
    const videoGalleryButtons = document.querySelector('.video-gallery-buttons');
    const videoGalleryTitle = document.querySelector('.video-gallery-title');
    const videoGalleryDesc = document.querySelector('.video-gallery-desc');

    let activeId = null;

    function thumbUrl(youtubeId) {
      return `https://i.ytimg.com/vi/${youtubeId}/hqdefault.jpg`;
    }

    function renderButtons() {
      videoGalleryButtons.innerHTML = '';
      galleryVideos.forEach(video => {
        const btn = document.createElement('button');
        btn.className = 'video-gallery-btn' + (video.id === activeId ? ' active' : '');
        btn.setAttribute('type', 'button');
        btn.setAttribute('aria-label', 'Show ' + video.title);
        btn.innerHTML = `
          <span class="video-gallery-label">
            <span class="video-gallery-label-title">${video.title}</span>
          </span>
        `;
        btn.addEventListener('click', () => selectVideo(video.id));
        videoGalleryButtons.appendChild(btn);
      });
    }

    // Shows a lightweight thumbnail + play button instead of loading the
    // YouTube iframe immediately. The real player only loads once clicked,
    // which keeps the page fast even with many videos listed.
    function renderFacade(video) {
      videoGalleryPlayer.innerHTML = `
        <div class="facade" style="background-image:url('${thumbUrl(video.youtubeId)}')" role="button" aria-label="Play ${video.title}">
          <span class="play-btn">
            <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
          </span>
        </div>
      `;
      videoGalleryPlayer.querySelector('.facade').addEventListener('click', () => loadPlayer(video));
    }

    function loadPlayer(video) {
      const videoIframe = document.createElement('iframe');
      videoIframe.src = `https://www.youtube.com/embed/${video.youtubeId}?autoplay=1&rel=0`;
      videoIframe.title = video.title;
      videoIframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
      videoIframe.allowFullscreen = true;
      videoGalleryPlayer.innerHTML = '';
      videoGalleryPlayer.appendChild(videoIframe);
    }

    function selectVideo(id) {
      const galleryVideo = galleryVideos.find(v => v.id === id);
      if (!galleryVideo) return;
      activeId = id;
      renderFacade(galleryVideo);
      videoGalleryTitle.textContent = galleryVideo.title;
      videoGalleryDesc.textContent = galleryVideo.subtitle || '';
      renderButtons();
    }

    // Initial state: first video selected
    const galleryVideos = loadVideosFromDOM();

    if(galleryVideos) {
      selectVideo(galleryVideos[0].id);
    }    
  },

  // function to quick escape from the current page
  safeEscape: () => {
    const btnEscape = document.querySelector('.btn-escape');
    if(btnEscape === null) return;

    btnEscape.addEventListener('click', () => {
      window.location.replace("https://weather.com");
    });

  },

  // function to display team member bio info
  teamBio: () => {
    const bioLinks = document.querySelectorAll('.member-info .btn-modal');

    if (bioLinks === null) return;

    bioLinks.forEach((link, index) => {      
      link.addEventListener('click', (e) => {
        let modal = document.querySelector('[data-modal="' + link.getAttribute('data-target') + '"]');
        const teamMoreText = link.closest('article').querySelector('.member-more').innerHTML;
        modal.querySelector('.modal-inner').innerHTML = teamMoreText;
      });
    })



  },



  // Function to be called when DOM is ready
  callback: () => {

      caminarlatino.mainNavReveal();
      caminarlatino.smoothNav();
      window.addEventListener('scroll', utilities.debounce(caminarlatino.detectScroll, 100));      
      caminarlatino.closeAlert();
      caminarlatino.clearForm();
      caminarlatino.tabs();
      caminarlatino.youtubeLoad();
      caminarlatino.showModal();
      caminarlatino.closeModal();
      caminarlatino.subMenuReveal();
      caminarlatino.dropDown();
      caminarlatino.paneReveal();
      caminarlatino.galleryVideos();
      caminarlatino.safeEscape();
      caminarlatino.teamBio();
  },


  onFullyLoaded: () => {
    caminarlatino.homeSliderFooter();
  }    


};








// Check if DOM is ready
if ( document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll)) {
  caminarlatino.callback();
} else {
  document.addEventListener('DOMContentLoaded', caminarlatino.callback);
}


// Layout-dependent work (carousel, anything measuring rendered dimensions)
if (document.readyState === "complete") {
  caminarlatino.onFullyLoaded();
} else {
  window.addEventListener("load", caminarlatino.onFullyLoaded);
}

//# sourceMappingURL=concat.js.map