export const menu = (routes, child = false) => {
    let parent =  '' ;
    return routes.reduce((arr,i) => {
        // parent = child ?

        if (i.meta.hasOwnProperty('menu') && i.meta.menu) {
            arr.push({
                title: i.meta.title ?? '',
                key: i.name,
                icon: i.meta.icon ?? null,
                path: i.path,
                children: i.hasOwnProperty('children') ? menu(i.children) : []
            })
        }
        return arr
    },[])
}
