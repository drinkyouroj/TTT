I just wanted to talk about the difference between "helpers", "applogic", and "appstorage".

Applogic = Things that are core to the system such as Posts, Categories, Follows, etc

Helpers = Things that help the core systems such as Solr, Instragraph (allows us to make pictures look cool), etc
	(We should move the ThreadedComments functions to Applogic and separate out its templating from the code when we have time)

AppStorage = Repositories where we abstract what the model should be doing.