<div>
<form action="/login" method="POST">
        <label for="id">Identifiant: </label>
        <input type="text" name="username" id="id" placeholder="Votre identifiant" />
        <label for="pass">Mot de passe:</label>
        <input type="password" name="password" id="pass" placeholder="Votre mot de passe" />
        <label for="keepConnexion">Mémoriser ma connexion</label>
        <input type="checkbox" name="keepConnexion" id="keepConnexion" />
        <button>Se connecter</button>
    </form>
    <form action="/signup/sendAgain" method="POST">
        <p>Mail non reçu?</p>
        <label for="mail">Votre mail:</label>
        <input type="email" name="email" id="mail" placeholder="Votre Mail" />
        <button>Renvoyer</button>
    </form>
</div>