/* The code is adding an event listener to the `DOMContentLoaded` event of the document. This
event is fired when the initial HTML document has been completely loaded and parsed, without waiting
for stylesheets, images, and subframes to finish loading. */
document.addEventListener('DOMContentLoaded', function() {
    runCompontentTabs();
    lazyLoad();
});


/**
 * The function `runCompontentTabs` sets up tab functionality on a webpage using JavaScript.
 */
function runCompontentTabs() {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabPanes = document.querySelectorAll('.tab-pane');

    let activeTab = get_set_storage() || tabLinks[0].dataset.tab;

    tabLinks.forEach(link => {
        link.addEventListener('click', () => {
            activateTab(link.dataset.tab, tabLinks, tabPanes);
        });
    });

    
    activateTab(activeTab, tabLinks, tabPanes);
    
    // activateByKey(tabLinks, tabPanes);
}

/**
 * The function `activateTab` is used to switch between tabs by adding and removing the 'active' class
 * based on the tab ID provided.
 * @param {string|int} tabId - The `tabId` parameter represents the ID of the tab that you want to activate.
 * @param {Elements|any} tabLinks - An array of HTML elements representing the tab navigation links.
 * @param {Elements|any} - The `tabPanes` parameter in the `activateTab` function refers to an array of
 * elements that represent the content panes associated with each tab. These panes are typically the
 * sections of content that are displayed when a specific tab is activated. The function iterates over
 * these panes to show
 */
function activateTab(tabId, tabLinks, tabPanes) {
    tabLinks.forEach(link => {
        link.classList.remove('active');
        if (link.dataset.tab === tabId) {
            link.classList.add('active');
        }
    });
    tabPanes.forEach(pane => {
        pane.classList.remove('active');
        if (pane.id === tabId) {
            pane.classList.add('active');
        }
    });

    get_set_storage(tabId);
}

/**
 * The function `get_set_storage` is used to get or set a value in the session storage based on the
 * current page's active tab.
 * @param {string|null} $value - The `value` parameter in the `get_set_storage` function is used to either
 * set or get the value stored in the session storage for a specific page and active tab. If a value is
 * provided when calling the function, it will set the value in the session storage for the current
 * page and active
 * @returns The `get_set_storage` function is returning the value stored in the sessionStorage under
 * the key `pagename + '_activeTab'`. If a value is provided as an argument to the function, it will
 * set the sessionStorage item with the key `pagename + '_activeTab'` to that value and return nothing.
 */
function get_set_storage($value=null) {
    const pagename = window.location.search.split('=').pop();
    if ($value != null) {
        return sessionStorage.setItem(pagename + '_activeTab', $value);
    } 
    return sessionStorage.getItem(pagename+'_activeTab');
}

/**
 * The function `activateByKey` allows for keyboard navigation between tabs using the ArrowRight and
 * ArrowLeft keys.
 * @param {Elements|any} tabLinks - `tabLinks` is a collection of elements representing the tabs in a tabbed
 * interface. These elements are typically links or buttons that users can click on to switch between
 * different tabs.
 * @param {Elements|any} tabPanes - TabPanes is an array-like object that contains the elements representing the
 * content panes associated with each tab link.
 */
function activateByKey(tabLinks, tabPanes) {
    document.addEventListener('keydown', (e) => {
        const activeLink = document.querySelector('.tab-link.active');
        
        let newIndex = Array.from(tabLinks).indexOf(activeLink);

        if (e.key === 'ArrowRight') {
            newIndex = (newIndex + 1) % tabLinks.length;
        } else if (e.key === 'ArrowLeft') {
            newIndex = (newIndex - 1 + tabLinks.length) % tabLinks.length;
        }

        activateTab(tabLinks[newIndex].dataset.tab, tabLinks, tabPanes);
    });
}


/**
 * The function `darkMode` toggles a dark mode theme on a webpage and saves the user's preference in
 * session storage.
 * @param {string} btnSelector - The `btnSelector` parameter in the `darkMode` function is used to specify the
 * selector for the button element that toggles the dark mode. This selector is used to select the
 * button element in the HTML document so that the dark mode functionality can be applied when the
 * button is clicked.
 * @param {string} ContainerSelector - The `ContainerSelector` parameter in the `darkMode` function is used to
 * specify the selector for the container element that will have the dark mode applied to it. This
 * selector is used to target the element in the DOM and add or remove the 'dark-mode' class based on
 * the user's preference
 */
function darkMode(btnSelector, ContainerSelector) {
    // JavaScript
    const toggleButton = document.querySelector(btnSelector);
    const body = document.querySelector(ContainerSelector);

    // Check for saved 'darkMode' in session storage
    let darkMode = sessionStorage.getItem('darkMode');

    if (darkMode === 'enabled') {
        body.classList.add('dark-mode');
        toggleButton.classList.add('active');
    }

    toggleButton.onclick = function() {
    
        body.classList.toggle('dark-mode');
        toggleButton.classList.toggle('active');

        // Save the user's preference in session storage
        if (body.classList.contains('dark-mode')) {
            sessionStorage.setItem('darkMode', 'enabled');
        } else {
            sessionStorage.setItem('darkMode', 'disabled');
        }
    };
}

// darkMode('#dark-mode-btn', 'body');

/* The line `jQuery( "div#wpwrap" ).addClass('optpage');` is using jQuery to select the div element
with the id "wpwrap" and add a class "optpage" to it. This will add the class "optpage" to the
selected div element, which can be used for styling or other purposes in the CSS or JavaScript code. */
jQuery( "div#wpwrap" ).addClass('optpage');


/**
 * The function `lazyLoad` sets the loading attribute of all img elements on the page to "lazy".
 */
function lazyLoad() {
    const _media = document.querySelectorAll('img');
    _media.forEach(media => {
        media.setAttribute('loading', "lazy");
    });
}




/**
 * The function encapsuler wraps div elements with unique classes in fieldset containers within
 * specified main containers.
 * @param {{ containerMain: string, containerWrap: string, item: string, customClass: string }} args 
 * @param {string} args.containerMain '.tab-pane', Get all div elements with the same class to start function
 * @param {string} args.containerWrap '.form-fields', It then appends the newly createdelement as a child of the selected element.
 * @param {string} args.item '.joined', This line is part of the `encapsuler` function and is used to find specific elements within each
 * container element that need to be wrapped in fieldset containers based on certain criteria.
 * @param {string} arg.customClass "take" variable is being used in the `encapsuler` function to specify a custom class prefix 
 * that is used to identify specific classes within the elements being processed.
 * 
 * @return {void}
 */
function encapsuler(args) {
    // Get all div elements with the same class
    let containers = document.querySelectorAll(args.containerMain);

    // Loop through each container and wrap it in a fieldset container
    containers.forEach(container => {
       // Create a new fieldset element
        let divs = container.querySelectorAll(args.item);
        if (divs.length < 1) return;

        // Get unique classes from div elements
        let uniqueClasses = [...new Set(Array.prototype.map.call(
            divs, div => div.classList[div.classList.length - 1]))];

        // Create wrappers and append to container
        uniqueClasses.forEach(className => {
            const wrapper = document.createElement('fieldset');
            let legend = document.createElement('legend');
            legend.textContent = className;
            wrapper.className = 'input-group wrapper';
            wrapper.id = className;
            wrapper.append(legend);
            container.querySelector(args.containerWrap).append(wrapper);

            // Move div elements to their corresponding wrappers
            divs.forEach(div => {
                if (div.classList[div.classList.length - 1] === className) {
                    wrapper.appendChild(div);

                    let takedClass = Array.prototype.find.call(
                        div.classList, className => className.startsWith(args.customClass)
                    );
                    if (takedClass !== undefined) {
                        wrapper.classList.add(takedClass);
                    }
                }
            });
        });
    });
}


/**
 * The function `adderClass` adds a specified class to all elements selected by a given CSS selector.
 * @param selector - The `selector` parameter is a CSS selector that specifies the elements to which
 * the class will be added.
 * @param addClass - The `addClass` parameter in the `adderClass` function is the class name that you
 * want to add to the selected elements.
 */
function adderClass(selector, addClass) {
    let elements = document.querySelectorAll(selector);
    elements.forEach(element => {
        element.classList.add(addClass);
    });
}

/**
 * The function `removerClass` adds a specified class to all elements selected by a given selector.
 * @param selector - The `selector` parameter in the `removerClass` function is a CSS selector that
 * specifies the elements to which the class should be removed. For example, you can pass a class name,
 * an ID, or any other valid CSS selector to target specific elements on the page.
 * @param removeClass - The `removeClass` parameter in the `removerClass` function is the class name
 * that you want to remove from the selected elements.
 */
function removerClass(selector, removeClass) {
    let elements = document.querySelectorAll(selector);
    elements.forEach(element => {
        element.classList.add(removeClass);
    });
}

/**
 * The function `takerClass` returns the first class name of an element that starts with a specified
 * prefix.
 * @param element - The `element` parameter is the element whose class list you want to search for a
 * class that starts with a specific prefix.
 * @param prefix - The `prefix` parameter is a string that represents the prefix that you want to
 * search for in the class names of the specified element. The function `takerClass` will search for a
 * class name in the element's class list that starts with this prefix.
 * @returns The function `takerClass` takes two parameters: `element` and `prefix`. It uses the
 * `Array.prototype.find` method to search for a class in the `element`'s class list that starts with
 * the specified `prefix`. The function will return the first class name that matches the prefix, or
 * `undefined` if no matching class is found.
 */
function takerClass(element, prefix) {
    return Array.prototype.find.call(element.classList, className => className.startsWith(prefix));
}

/**
 * CALLBACK FUNCTIONS 
 */

encapsuler({
    containerMain: '.tab-pane',
    containerWrap: '.form-fields',
    item: '.joined',
    customClass: "take"
});
adderClass('.form-fields', "grid")