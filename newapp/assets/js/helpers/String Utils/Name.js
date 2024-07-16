class StringUtils {
    static personName(name){
        let newName = "";
        name.toLowerCase().split(' ').forEach(element => {
            element = element.trim().replace(element.charAt(0), element.charAt(0).toUpperCase());
            newName +=  `${element} `;
        });
        return newName;
    }
}