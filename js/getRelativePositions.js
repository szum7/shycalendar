$.fn.offset = (function (options) {
    var elem = this[0],
        doc = elem && elem.ownerDocument;

    if (!doc) {
        return null;
    }

    if (elem === doc.body) {
        return jQuery.offset.bodyOffset(elem);
    }

    return getOffset(elem, doc, doc.documentElement);
});

function getOffset(elem, doc, docElem, box) {
    try {
        box = elem.getBoundingClientRect();
    } catch(e) {}

    // Make sure we're not dealing with a disconnected DOM node
    if (!box || !jQuery.contains(docElem, elem)) {
        return box ? {
            top: box.top,
            left: box.left
        } : {
            top: 0,
            left: 0
        };
    }

    var body = doc.body,
        win = getWindow(doc),
        clientTop = docElem.clientTop || body.clientTop || 0,
        clientLeft = docElem.clientLeft || body.clientLeft || 0,
        scrollTop = win.pageYOffset || jQuery.support.boxModel && docElem.scrollTop || body.scrollTop,
        scrollLeft = win.pageXOffset || jQuery.support.boxModel && docElem.scrollLeft || body.scrollLeft,
        top = box.top + scrollTop - clientTop,
        left = box.left + scrollLeft - clientLeft;

    return {
        top: top,
        left: left
    };
}