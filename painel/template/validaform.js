function validaCadastro() { 
    var nome = form1.nome.value; 
    var email = form1.email.value; 
    var senha = form1.senha.value; 
    var senha2 = form1.senha2.value;  
    var login = form1.login.value;  
    var nasc = form1.nasc.value;  
    
    if(nome.length < 3)
    {
        alert('Nome inválido, deve conter mais de 2 caracteres.\n\
(Invalid Name, must contain more than 2 characters).');
        form1.nome.focus();
        return false;    
    }
    
    if(login.length < 3|| login.length > 15)
    {
        alert('Login inválido, deve conter entre 3 e 15 caracteres.\n\
(Invalid ID, must be between 3 and 15 characters).')
        form1.login.focus();
        return false;    
    }
    
    if(senha.length < 5 || senha.length > 15)
    {
        alert('Senha inválido, deve conter entre 5 e 15 caracteres.\n\
(Invalid password, must be between 3 and 15 characters).')
        form1.senha.focus();
        return false;    
    }
    
    if(senha!=senha2)
    {
        alert('As senhas não correspondem.\n\
(The passwords not match).')
        form1.senha2.focus();
        return false;    
    }
    
    if(nasc.length != 10)
    {
        alert('Data inválida\n\
(Invalid Date).')
        form1.nasc.focus();
        return false;    
    }
    

    var usuario = email.substring(0, email.indexOf("@")); 
    var dominio = email.substring(email.indexOf("@")+ 1, email.length); 
    if ((usuario.length >=1) && (dominio.length >=3) && 
            (usuario.search("@")==-1) && (dominio.search("@")==-1) && 
            (usuario.search(" ")==-1) && (dominio.search(" ")==-1) && 
            (dominio.search(".")!=-1) && (dominio.indexOf(".") >=1)&& 
            (dominio.lastIndexOf(".") < dominio.length - 1)) 
    { 
    } else{ 
        alert('Email inválido\n\
(Invalid email).')
        form1.email.focus();
        return false;  
    }   
}