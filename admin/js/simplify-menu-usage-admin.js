(function($){
    'use strict';

    var $document = $(document);
    var api = wpNavMenu;
    const i18nApi = wp.i18n;
   

    var extendedApi = {
        behaviors: {
            'default': i18nApi.__('default','simplify-menu-usage'),
            'take-children': i18nApi.__('take-children','simplify-menu-usage'),
            'same-level': i18nApi.__('same-level','simplify-menu-usage')
        },
        downwardsMoveBehavior: obj.insert_downwards_behavior,
        
        changeInsertDownwardsBehaviorTemp: function(currentBehavior){
            this.downwardsMoveBehavior = currentBehavior;
            $('span.current-behavior').html(this.behaviors[currentBehavior]);
            $('.toggle-simplify-menu-usage').attr('data-handling', currentBehavior);   
        },
        
        setDisabledMoveButtonAttribute: function(element, selector, disabledStatus){
            element.find(selector).prop('disabled', disabledStatus);
        },
        
        hideOutterMoveButtons: function(){
            $('.custom-field-move').removeClass('not-active');
            var items = $('#update-nav-menu #menu-to-edit .menu-item');
            var menuItemsLength = items.length;
            
            $.each(items, function(i, elm){
                var currentMenuDepth = extendedApi.extractMenuItemAttr($(this), 'depth');
                var baseLevelItemsAmount = $('#menu-to-edit li.menu-item.menu-item-depth-'+currentMenuDepth).length;
                var indexOfItemInBaseLevel = $('#menu-to-edit li.menu-item.menu-item-depth-'+currentMenuDepth).index($(this));
                var fieldMoveWrapper = $(this).find('.item-controls .custom-field-move');
                var currentElm = $(this);
                
                if(i === 0){
                    extendedApi.setDisabledMoveButtonAttribute(fieldMoveWrapper, '.custom-menus-move-up', true);
                    extendedApi.setDisabledMoveButtonAttribute(fieldMoveWrapper, '.custom-menus-move-right', true);
                    extendedApi.setDisabledMoveButtonAttribute(fieldMoveWrapper, '.custom-menus-move-left', true);
                    if(indexOfItemInBaseLevel === baseLevelItemsAmount - 1){
                       extendedApi.setDisabledMoveButtonAttribute(fieldMoveWrapper, '.custom-menus-move-down', true); 
                    }else{
                       extendedApi.setDisabledMoveButtonAttribute(fieldMoveWrapper, '.custom-menus-move-down', false);
                    }
                }else{
                    displayUpDownButton(items, currentElm, currentMenuDepth);
                    displayLeftRightMoveButton(items, currentElm, i);
                }                
                
                //handle left move appearance quick position
                if(i < menuItemsLength - 1){
                    var currentDepth = extendedApi.extractMenuItemAttr(items.eq(i), 'depth');
                    var nextDepth = extendedApi.extractMenuItemAttr(items.eq(i + 1), 'depth'); 
                    if(currentDepth < nextDepth){
                        $(this).find('.quick-positions .move-out-element').addClass('disabled');
                    }else{
                        $(this).find('.quick-positions .move-out-element').removeClass('disabled');
                    }
                }
            });
            
            function displayUpDownButton(items, currentElm, currentMenuDepth){
                var isPossibleElementFound = false;
                var acceptableMenuDepth = currentMenuDepth;
                var i;
                
                /* this part checks if a following menu item exists that has either 
                 * the base menu depth from the menu item started or is maximum one step
                 * more left - this would be an acceptable menu item to move to */
                if(items.index(currentElm) === items.length - 1){
                    extendedApi.setDisabledMoveButtonAttribute(currentElm, '.custom-menus-move-down', true);
                }else{
                    for(i = items.index(currentElm) + 1; i < items.length; ++i){
                        var tmpItemMenuDepth = extendedApi.extractMenuItemAttr(items.eq(i), 'depth');
                        if(acceptableMenuDepth === tmpItemMenuDepth
                           || acceptableMenuDepth - 1 === tmpItemMenuDepth){
                            isPossibleElementFound = true;
                            break;
                        }
                    }
                    if(isPossibleElementFound){
                        extendedApi.setDisabledMoveButtonAttribute(currentElm, '.custom-menus-move-down', false);
                        extendedApi.setDisabledMoveButtonAttribute(currentElm, '.custom-menus-move-up', false);
                    }else{
                        extendedApi.setDisabledMoveButtonAttribute(currentElm, '.custom-menus-move-down', true);
                    }
                }
            }
            
            function displayLeftRightMoveButton(items, elm, currentIndex){
                var rightDisabledStatus = true;
                var leftDisabledStatus = true;
                var upperSibling = items.eq(currentIndex - 1);
                
                if(extendedApi.extractMenuItemAttr(upperSibling, 'depth') 
                    >= extendedApi.extractMenuItemAttr(elm, 'depth')){
                    rightDisabledStatus = false;
                }
                
                if(currentIndex > 0 && extendedApi.extractMenuItemAttr(elm, 'depth') >= 1){
                    leftDisabledStatus = false;
                }
                extendedApi.setDisabledMoveButtonAttribute(elm, '.custom-menus-move-right', rightDisabledStatus);
                extendedApi.setDisabledMoveButtonAttribute(elm, '.custom-menus-move-left', leftDisabledStatus);
            }
        },
        
        extractMenuItemAttr: function(elm, target){
            if(target === 'depth'){
                var targetAttr = 'class';
                var regex = /menu-item-depth-[0-9]+/g;
                var replacedStr = 'menu-item-depth-';
            }else if(target === 'id'){
                var targetAttr = 'id';
                var regex = /menu-item-[0-9]+/g;
                var replacedStr = 'menu-item-';
            }else{
                return 0;
            }
            
            var extractedAttr = elm.attr(targetAttr);
            var foundMatches = extractedAttr.match(regex);
            var index = 0;

            if(foundMatches === null){
                return 0;
            }
            if(foundMatches.length === 1){
                index = parseInt(foundMatches[0].replace(replacedStr, '', foundMatches));
            }
            return index;
        },
        
        displayQuickPositions: function(){
            var quickPositions = $('.menu-simplify-usage.quick-positions');
            var numberActiveCheckboxes = 0;
            $('#nav-menu-meta input[type="checkbox"]').each(function(i, elm){
                /* check if select-all class does not exist on element,
                 * there is a scenario if just one item exists, the all checkbox 
                 * is selected as well and leads to issues inserting the chosen item */
                if($(this).is(':checked') && !$(this).hasClass('select-all')){
                    numberActiveCheckboxes += 1;
                }
            });
            if(numberActiveCheckboxes === 0){
                quickPositions.removeClass('visible');
            }else{
                quickPositions.addClass('visible');
            }
        },
        
        highlightMenuItem: function(elm){
            if(elm.hasClass('found')){
                elm.removeClass('found');
            }else{
                elm.addClass('found');
            }
        },
        
        insertMenuItemQuickPosition: function(direction, anchorElementId){
            
            var directions = ['up', 'down', 'left', 'right'];
            if($('#menu-item-'+anchorElementId).length 
                && ($.inArray(direction, directions) !== -1)
                && $('#menu-to-edit').length !== 0){
                
                var re = /menu-item\[([^\]]*)/;
                $('#nav-menu-meta input[type="checkbox"]').each(function(i, elm){
                    if($(this).is(':checked') && !$(this).hasClass('select-all')){
                        var menuItems = {};
                        var menuItem = $(this);
                        var listItemDBIDMatch = re.exec(menuItem.attr('name'));
                        var listItemDBID = 'undefined' == typeof listItemDBIDMatch[1] ? 0 : parseInt(listItemDBIDMatch[1], 10);

                        menuItem.closest('.accordion-section-content').find('.button-controls .spinner').addClass('is-active');
                        menuItems[listItemDBID] = menuItem.closest('li').getItemData('add-menu-item', listItemDBID);
                        setTimeout(function(){                        
                            api.addItemToMenu(menuItems, insertRetrievedMenuItem, function(){
                                // Deselect the items and hide the Ajax spinner.
                                $('#nav-menu-meta input[type="checkbox"]').prop('checked', false);
                                $('.menu-simplify-usage.quick-positions').removeClass('visible');
                                $('.menu-simplify-usage.quick-positions a').removeClass('disabled');
                                menuItem.closest('.accordion-section-content').find('.button-controls .spinner').removeClass('is-active');
                                api.refreshAdvancedAccessibility();
                            });
                        }, 350);
                    }
                });
            }
            

            function insertRetrievedMenuItem(menuMarkup, params){
                var elms = $('#menu-to-edit li');
                var anchorElement = $('li#menu-item-'+anchorElementId);
                var anchorElementMenuDepth = extendedApi.extractMenuItemAttr(anchorElement, 'depth');
                var parentId = anchorElement.find('.menu-item-data-parent-id').val();
                var parentIndex = elms.index(anchorElement);
                var insertedItemIndex = 0;

                if(direction === 'up'){
                    insertedItemIndex = parentIndex;
                    menuMarkup = prepareReplacedMenuMarkup(menuMarkup, anchorElementMenuDepth);
                    anchorElement.before(menuMarkup);
                }else if(direction === 'down'){
                    insertedItemIndex = parentIndex + 1;
                    /*
                     * cases: 
                     * following element is more left -> just insert below
                     * following element is same level -> just insert below
                     * last element -> just insert below
                     * following element is more right (submenu children elements)
                     *  go to last position with the higher depth
                     */
                    
                    if(parentIndex < (elms.length - 1)){
                        if(extendedApi.extractMenuItemAttr(elms.eq(parentIndex + 1), 'depth') > extendedApi.extractMenuItemAttr(elms.eq(parentIndex), 'depth')){
                            if(extendedApi.downwardsMoveBehavior === 'default'){
                                anchorElementMenuDepth += 1;
                                parentId = anchorElementId;
                            }else if(extendedApi.downwardsMoveBehavior === 'same-level'){
                                var i;
                                var depthStatus = extendedApi.extractMenuItemAttr(elms.eq(parentIndex + 1), 'depth');
                                var needInsertingAfterElement = true;

                                for(i = parentIndex + 1; i < elms.length; ++i){
                                    var tmpDepthStatus = extendedApi.extractMenuItemAttr(elms.eq(i), 'depth');
                                    if(tmpDepthStatus < depthStatus){
                                        needInsertingAfterElement = false;
                                        break;
                                    }
                                }
                                if(needInsertingAfterElement){
                                    /* reached bottom - there was no other element with the anchor depth level
                                     * therefore new anchor element is last item */
                                    if(elms.length === i){
                                        anchorElement = elms.eq(i-1);
                                    }else{
                                        anchorElement = elms.eq(i);
                                    }
                                }else{
                                    anchorElement = elms.eq(i - 1);
                                }
                                insertedItemIndex = i;
                            }
                        }
                    }
                    
                    menuMarkup = prepareReplacedMenuMarkup(menuMarkup, anchorElementMenuDepth);
                    anchorElement.after(menuMarkup);
                }else if(direction === 'right'){
                    insertedItemIndex = parentIndex + 1;
                    parentId = anchorElementId;
                    menuMarkup = prepareReplacedMenuMarkup(menuMarkup, anchorElementMenuDepth + 1);
                    anchorElement.after(menuMarkup);
                }else if(direction === 'left'){
                    insertedItemIndex = parentIndex + 1;
                    var parentIDOfAnchorElement = anchorElement.find('.menu-item-data-parent-id').val();
                    parentId = $('#menu-item-'+parentIDOfAnchorElement).find('.menu-item-data-parent-id').val();
                    menuMarkup = prepareReplacedMenuMarkup(menuMarkup, anchorElementMenuDepth - 1);
                    anchorElement.after(menuMarkup);
                }

                var elms = $('#menu-to-edit li');
                elms.eq(insertedItemIndex).find('.menu-item-data-parent-id').val(parentId);

                /* inserting an item to the left requires getting the grandparent id
                 * as the inserted item leaves the anchor element parent relationship;
                 * checking new child elements and adjust the parent id to inserted item id */
                if(direction === 'left' || (direction === 'down' && extendedApi.downwardsMoveBehavior === 'take-children')){
                    adjustChildrenElements(elms, insertedItemIndex);
                }
            }
            
            function adjustChildrenElements(elms, insertedItemIndex){
                var elmsLength = elms.length;
                var i = insertedItemIndex + 1;
                var insertItemdId = extendedApi.extractMenuItemAttr(elms.eq(insertedItemIndex), 'id');
                var insertedItemMenuDepth = extendedApi.extractMenuItemAttr(elms.eq(insertedItemIndex), 'depth');

                for(i = insertedItemIndex + 1; i < elmsLength - 1; ++i){
                    var nextElementDepth = extendedApi.extractMenuItemAttr(elms.eq(i), 'depth');
                    if(insertedItemMenuDepth < nextElementDepth){
                        if(insertedItemMenuDepth === nextElementDepth - 1){
                            elms.eq(i).find('.menu-item-data-parent-id').val(insertItemdId);
                        }
                    }else{
                        break;
                    }
                }
            }

            function prepareReplacedMenuMarkup(menuMarkup, menuDepth){
                var toBeReplaced = 'menu-item-depth-'+menuDepth;
                menuMarkup = menuMarkup.replace('menu-item-depth-0', toBeReplaced);
                return menuMarkup;
            }        
        }
    };
    
    api.moveMenuItem = function($this, dir){
        var items, newItemPosition, newDepth,
            menuItems = $('#menu-to-edit li'),
            menuItemsCount = menuItems.length,
            thisItem = $this.parents('li.menu-item'),
            thisItemChildren = thisItem.childMenuItems(),
            thisItemData = thisItem.getItemData(),
            thisItemDepth = parseInt(thisItem.menuItemDepth(), 10),
            thisItemPosition = parseInt(thisItem.index(), 10),
            nextItem = thisItem.next(),
            nextItemChildren = nextItem.childMenuItems(),
            nextItemDepth = parseInt(nextItem.menuItemDepth(), 10) + 1,
            prevItem = thisItem.prev(),
            prevItemDepth = parseInt(prevItem.menuItemDepth(), 10),
            prevItemId = prevItem.getItemData()['menu-item-db-id'];

        switch (dir){
        case 'up':
            newItemPosition = thisItemPosition - 1;

            // Already at top.
            if(0 === thisItemPosition)
                break;

            // If a sub item is moved to top, shift it to 0 depth.
            if(0 === newItemPosition && 0 !== thisItemDepth)
                thisItem.moveHorizontally(0, thisItemDepth);

            // If prev item is sub item, shift to match depth.
            if(0 !== prevItemDepth)
                thisItem.moveHorizontally(prevItemDepth, thisItemDepth);

            // Does this item have sub items?
            if(thisItemChildren){
                items = thisItem.add(thisItemChildren);
                // Move the entire block.
                items.detach().insertBefore(menuItems.eq(newItemPosition)).updateParentMenuItemDBId();
            } else {
                thisItem.detach().insertBefore(menuItems.eq(newItemPosition)).updateParentMenuItemDBId();
            }
            break;
        case 'down':
            // Does this item have sub items?
            if(thisItemChildren.length !== 0){
                items = thisItem.add(thisItemChildren),
                    nextItem = menuItems.eq(items.length + thisItemPosition),
                    nextItemChildren = 0 !== nextItem.childMenuItems().length;

                if(nextItemChildren){
                    var toBeInsertedAfter;
                    switch(extendedApi.downwardsMoveBehavior){
                        case 'default':
                            newDepth = parseInt(nextItem.menuItemDepth(), 10) + 1;
                            thisItem.moveHorizontally(newDepth, thisItemDepth); 
                            toBeInsertedAfter = menuItems.eq(thisItemPosition + items.length);
                            break;
                        case 'same-level':
                            var nextItemLength = nextItem.childMenuItems().length;
                            toBeInsertedAfter = menuItems.eq(thisItemPosition + items.length + nextItemLength);
                            break;
                        case 'take-children':
                            var thisItemId = thisItem.getItemData()['menu-item-db-id'];
                            $.each(nextItem.childMenuItems(), function(){
                                if(thisItemDepth === extendedApi.extractMenuItemAttr($(this), 'depth') - 1){
                                    $(this).find('.menu-item-data-parent-id').val(thisItemId);
                                }
                            });
                            toBeInsertedAfter = menuItems.eq(thisItemPosition + items.length);
                            break;
                    }
                    items.detach().insertAfter(toBeInsertedAfter).updateParentMenuItemDBId();
                    break;
                }

                // Have we reached the bottom?
                if(menuItemsCount === thisItemPosition + items.length)
                    break;

                items.detach().insertAfter(menuItems.eq(thisItemPosition + items.length)).updateParentMenuItemDBId();
            } else {
                // If next item has sub items, shift depth.
                /* 
                 * custom functionality: checking for different behaviors to keep
                 * same usability as user would insert menu items from the left sidebar
                 * via quick position arrows
                 */
                if(0 !== nextItemChildren.length){
                    var toBeInsertedAfter;
                    switch(extendedApi.downwardsMoveBehavior){
                        case 'default':
                            thisItem.moveHorizontally(nextItemDepth, thisItemDepth);
                            toBeInsertedAfter = menuItems.eq(thisItemPosition + 1);
                            break;
                        case 'same-level':
                            var i;
                            var depthStatus = extendedApi.extractMenuItemAttr(nextItem.next(), 'depth');
                             /* finding the position when the submenu ends and take the previous item
                             * to insert the the moving item after the submenu */
                            for(i = menuItems.index(nextItem.next()); i < menuItemsCount; ++i){
                                var tmpDepthStatus = extendedApi.extractMenuItemAttr(menuItems.eq(i), 'depth');
                                if(tmpDepthStatus < depthStatus){
                                    break;
                                }
                            }
                            toBeInsertedAfter = menuItems.eq(i - 1);
                            break;
                        case 'take-children':
                            var thisItemId = thisItem.getItemData()['menu-item-db-id'];
                            $.each(nextItemChildren, function(){
                                if(thisItemDepth === extendedApi.extractMenuItemAttr($(this), 'depth') - 1){
                                    $(this).find('.menu-item-data-parent-id').val(thisItemId);
                                }
                            });
                            toBeInsertedAfter = menuItems.eq(thisItemPosition + 1);
                            break;
                    }
                    thisItem.detach().insertAfter(toBeInsertedAfter).updateParentMenuItemDBId();
                    break;
                }
                // Have we reached the bottom?
                if(menuItemsCount === thisItemPosition + 1)
                    break;
                thisItem.detach().insertAfter(menuItems.eq(thisItemPosition + 1)).updateParentMenuItemDBId();
            }
            break;
        case 'top':
            // Already at top.
            if(0 === thisItemPosition)
                break;
            // Does this item have sub items?
            if(thisItemChildren){
                items = thisItem.add(thisItemChildren);
                // Move the entire block.
                items.detach().insertBefore(menuItems.eq(0)).updateParentMenuItemDBId();
            } else {
                thisItem.detach().insertBefore(menuItems.eq(0)).updateParentMenuItemDBId();
            }
            break;
        case 'left':
            // As far left as possible.
            if(0 === thisItemDepth)
                    break;
            thisItem.shiftHorizontally(-1);
            /* check if shifted item has become a submenu parent
             * if thisItem and nextItem are on same depth level, thisItem will
             * become a submenu when moving one step left, therefore adjust parent-id
             */
            if(thisItemDepth === extendedApi.extractMenuItemAttr(nextItem, 'depth')){
                var thisItemId = thisItem.getItemData()['menu-item-db-id'];
                var thisItemIndex = menuItems.index(thisItem);
                for(i = thisItemIndex + 1; i < menuItemsCount; ++i){
                    var currentElm = menuItems.eq(i);
                    if(thisItemDepth === extendedApi.extractMenuItemAttr(currentElm, 'depth')){
                        currentElm.find('.menu-item-data-parent-id').val(thisItemId);
                    }else{
                        break;
                    }
                }
            }
            break;
        case 'right':
            // Can't be sub item at top.
            if(0 === thisItemPosition)
                    break;
            // Already sub item of prevItem.
            if(thisItemData['menu-item-parent-id'] === prevItemId)
                    break;
            thisItem.shiftHorizontally(1);
            break;
        }
        $this.focus();
        api.registerChange();
        api.refreshKeyboardAccessibility();
        api.refreshAdvancedAccessibility();
    },
    
    api.addItemToMenu = function(menuItem, processMethod, callback) {
        var menu = $('#menu').val(),
                nonce = $('#menu-settings-column-nonce').val(),
                params;

        processMethod = processMethod || function(){};
        callback = callback || function(){};

        params = {
                'action': 'add-menu-item',
                'menu': menu,
                'menu-settings-column-nonce': nonce,
                'menu-item': menuItem
        };

        $.post( ajaxurl, params, function(menuMarkup) {

            menuMarkup = $.trim( menuMarkup ); // Trim leading whitespaces.
            processMethod(menuMarkup, params);

            callback();
            $('li.menu-item.pending').removeClass('pending');

        });
},
    
    api.updateQuickSearchResults = function(input){
        var panel, params,
                minSearchLength = 2,
                q = input.val();

        if(q.length < minSearchLength || api.lastSearch == q){
                return;
        }

        api.lastSearch = q;
        panel = input.parents('.tabs-panel');
        params = {
                'action': 'custom_menu_quick_search',
                'response-format': 'markup',
                'menu': $('#menu').val(),
                'menu-settings-column-nonce': $('#menu-settings-column-nonce').val(),
                'q': q,
                'type': input.attr('name'),
        };

        $('.spinner', panel).addClass('is-active');
        $.post(ajaxurl, params, function(menuMarkup){
            api.processQuickSearchQueryResponse(menuMarkup, params, panel);
        });
    },

    api.refreshAdvancedAccessibility = function(){
        // Hide all the move buttons by default.
        $('.menu-item-settings .field-move .menus-move').hide();
        // Mark all menu items as unprocessed.
        $('a.item-edit').data('needs_accessibility_refresh', true);
        // All open items have to be refreshed or they will show no links.
        $('.menu-item-edit-active a.item-edit').each(function(){
            api.refreshAdvancedAccessibilityOfItem(this);
        });
        extendedApi.hideOutterMoveButtons();
    },

    $document.ready(function(){
        $('#nav-menu-settings-explainations').insertBefore('#menu-to-edit');
        $('.custom-field-move').removeClass('not-active');
        
        $('#nav-menu-settings-explainations a.toggle-simplify-menu-usage').on('click', function(e){
            e.preventDefault();
            var currentBehavior = $(this).attr('data-handling');
            var possibleBehaviors = ['default', 'same-level', 'take-children'];
            var behaviorIndex = $.inArray(currentBehavior, possibleBehaviors);
            if(behaviorIndex !== -1){
                if(behaviorIndex === (possibleBehaviors.length - 1)){
                    extendedApi.changeInsertDownwardsBehaviorTemp(possibleBehaviors[0]);
                }else{
                    extendedApi.changeInsertDownwardsBehaviorTemp(possibleBehaviors[behaviorIndex + 1]);
                }
            }else{
                extendedApi.changeInsertDownwardsBehaviorTemp(possibleBehaviors[0]);
            }
        });
        
        $('.add-to-menu input.button').on('click', function(){
            $('.menu-simplify-usage.quick-positions').removeClass('visible');
        });
        
        extendedApi.hideOutterMoveButtons();
        $('.menu-item-checkbox').each(function(i, elm){
            if($.inArray($(this).val().toString(), obj.menu_items) !== -1){
                $(this).parent().append('<span class="dashicons dashicons-admin-links"></span>');
            }
        });
        
        $document.on('click', 'a.custom-nav-item-delete', function(e){
            return api.eventOnClickMenuItemDelete(e.currentTarget);
        });
        
        $document.on('mouseenter mouseleave', '#side-sortables label.menu-item-title', function(){
            var hoveredItemId = $(this).find('input').val();
            var foundElements = $('#menu-to-edit input.menu-item-data-object-id');
            var matchingElements = foundElements.filter('input[value="'+hoveredItemId+'"]');
            matchingElements.each(function(){
                var menuItem = $(this).closest('li.menu-item');
                extendedApi.highlightMenuItem(menuItem);
            });
        });
        
        $document.on('mouseenter mouseleave', '.menu-simplify-usage.quick-positions', function(){
            var currentElm = $(this).closest('li.menu-item');
            extendedApi.highlightMenuItem(currentElm);
        });
        
        $document.on('click', '#nav-menu-meta input[type="checkbox"]', function(){
            extendedApi.displayQuickPositions();
        });
        
        $document.on('click',  '.menu-simplify-usage.quick-positions a', function(e){
            e.preventDefault();
            $('.menu-simplify-usage.quick-positions a').addClass('disabled');
            var direction = $(this).data('dir');
            var anchorElement = $(this).parent().data('element');
            extendedApi.insertMenuItemQuickPosition(direction, anchorElement);
        });
        
        $document.on('click', '.menu-item .custom-field-move button', function(){
            extendedApi.hideOutterMoveButtons();
        });
        
        $document.on('click', '.smu-dismiss-rating-banner', function(e){
            e.preventDefault();
            var spinner = $(this).parent().find('.spinner');
            var nonce = $(this).data('nonce');
            spinner.addClass('show');
            
            $.ajax({
                type : "post",
                dataType : "json",
                url : ajaxurl,
                data : {
                    action: "smu_dismiss_rating_banner", 
                    _wpnonce: nonce
                },
                success: function(response){
                    spinner.removeClass('show');
                    $('.smu-rating-banner').remove();
                    console.log(response.data);  
                },
                error: function(xhr, error, status){
                    spinner.removeClass('show');
                    $('.smu-rating-banner').remove();
                    console.log(error, status);
                }
            });
        });
    });
        
})(jQuery);