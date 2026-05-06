<?php

namespace Idearia;

defined( 'ABSPATH' ) || exit;

/**
 * Accedi alle opzioni serializzate di WordPress usando la sintassi
 * di Laravel.
 *
 * Si tratta di un wrapper di get_option() che permette di
 * usare la sintassi
 *   config('sezione.opzione')
 * per accedere alle opzioni, invece di dover fare
 *   $sezione_x = get_option('sezione_x)
 *   $opzione_y = $sezione_x['opzione_y]
 *
 * Estendi la classe per:
 * - Definire dei valori di default, tramite l'override di getDefaults()
 * - Definire una scorciatoia per non dover digitare ogni volta tutto il
 *   nome della sezione, tramite l'override di $db_prefix.
 * - Effettuare una validazione o sanitizzazione delle opzioni, tramite
 *   l'override di config()
 */
abstract class Config
{
    /**
     * Opzionalmente, specifica i valori di default da 
     * quando non viene trovata un'opzione a database.
     * 
     * Esempio:
     * return [
     *		'tavoli.management_mode'  => 'standard',
     *		'tavoli.default_staff_id' => null,
     *		'impostazioni_ristorante.max_capacity_per_turno' => 50,
     *		'impostazioni_sistema.max_giorni_gestione_turni' => 20,
     *	];
     *
     * NOTA BENE: Volendo qui puoi anche inserire opzioni che non
     * esistono a database o nelle pagine di settings. È un buon modo
     * per avere un controllo centralizzato di quelle opzioni che non
     * hai ancora esposto all'utente.
     */
    public static function getDefaults(): array
    {
        return [];
    }

    /**
     * Opzionalmente, specifica un prefisso da applicare ai nomi delle
     * opzioni.
     *
     * Ad esempio, se il prefisso è 'test_' chiamando
     *   config('foo')
     * verrà ritornata l'opzione test_foo.
     */
    protected static $db_prefix = '';

    /**
     * Accedi alle opzioni senza curarti di dove sono immagazzinate.
     *
     * Ad esempio, per accedere all'elemento foo contenuto nell'opzione
     * serializzata bar, basta chiamare config( 'bar.foo' ). Per accedere
     * all'intera opzione serializzata, basta chiamare config( 'bar' ).
     *
     * Se l'opzione non esiste, verrà ritornato il valore di default
     * dato come secondo argomento; se questi è null, verrà ritornato
     * il valore di default definito in getDefaults; se non esiste
     * nemmeno questo, verrà ritornato null.
     *
     * Il modello è quello della funzione config di Laravel:
     * https://laravel.com/docs/8.x/helpers#method-config
     *
     * @throws Exception Quando viene passata una stringa vuota o invalida.
     *
     * @todo Aggiungi ricorsività
     */
    public static function config( string $query, $default = null )
    {
        if ( ! $query ) {
            throw new \Exception( 'Passato valore vuoto o invalido a config', 1 );
        }

        $default = $default ?? static::getDefaultValue( $query );

        if ( static::$db_prefix ) {
            $query = static::$db_prefix . $query;
        }

        $tokens = explode( '.', $query );

        $option_key = $tokens[0];

        $option_value = get_option( $option_key, $default );

        if ( count( $tokens ) === 1 ) {
            return $option_value;
        }

        return $option_value[ $tokens[1] ] ?? $default;
    }

    /**
     * Data un'opzione, ritorna il suo valore di default;
     * se non è definito, ritorna null
     */
    public static function getDefaultValue( string $query )
    {
        static $defaults_cache = [];

        $class = static::class;

        if ( ! isset( $defaults_cache[ $class ] ) ) {
            $defaults_cache[ $class ] = static::getDefaults();
        }

        return $defaults_cache[ $class ][ $query ] ?? null;
    }
}
